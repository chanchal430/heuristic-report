<template>
  <div class="app-layout">
    <SidebarNavigation 
      :sections="reportData ? reportData.sections : []" 
      :activeSection="activeSection"
      @export-pdf="handleExportPdf"
      @filter-severity="(sev) => updateFilters({ severity: sev })"
    />
    
    <div class="main-container">
      <div id="section-metadata">
        <ReportHeader :reportData="reportData" @export-pdf="handleExportPdf" />
      </div>

      <main class="content-wrapper">
        <div id="section-overview">
          <QuickOverview v-if="reportData" :data="reportData" />
        </div>
        
        <div id="section-summary">
          <ExecutiveSummary v-if="reportData" :data="reportData" />
        </div>

        <Filters v-if="reportData" :filters="filters" @update:filters="updateFilters" />
        
        <div id="section-findings" class="findings-grid" v-if="filteredFindings.length">
          <h2 class="section-title">Heuristic Findings</h2>
          <transition-group name="fade-slide" tag="div" class="findings-list">
            <FindingCard 
              v-for="finding in filteredFindings" 
              :key="finding.id" 
              :finding="finding" 
              @view-details="openModal" 
            />
          </transition-group>
        </div>
        <div v-else-if="reportData" class="empty-state">
          No findings match the current filters.
        </div>

        <div id="section-insights">
          <ReportConclusion v-if="reportData" />
        </div>

        <div id="section-appendix">
          <AppendixData v-if="reportData" :findings="reportData.findings" />
        </div>
      </main>
      <Modal v-if="selectedFinding" :finding="selectedFinding" @close="selectedFinding = null" />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import SidebarNavigation from './components/SidebarNavigation.vue';
import ReportHeader from './components/ReportHeader.vue';
import QuickOverview from './components/QuickOverview.vue';
import ExecutiveSummary from './components/ExecutiveSummary.vue';
import Filters from './components/Filters.vue';
import FindingCard from './components/FindingCard.vue';
import Modal from './components/Modal.vue';
import ReportConclusion from './components/ReportConclusion.vue';
import AppendixData from './components/AppendixData.vue';

const reportData = ref(null);
const activeSection = ref('metadata');
const rawPayload = ref({
  project_name: 'E-Commerce Checkout Redesign',
  evaluated_by: 'UX Team Alpha',
  version: '1.2.0',
  findings: [
    {
      id: 'FND-001',
      title: 'Missing error message on invalid email',
      severity: 'High',
      description: 'When a user enters an invalid email format, no inline validation error appears until submission.',
      evidence: 'Screenshot of form with "user@com" without highlighting.',
      recommendation: 'Add inline validation on blur event for the email field.',
      heuristic_id: 'H5'
    },
    {
      id: 'FND-002',
      title: 'Checkout progress is unclear',
      severity: 'Medium',
      description: 'Users do not know how many steps are left in the checkout process.',
      evidence: 'User testing video where 2/5 participants asked "am I almost done?"',
      recommendation: 'Implement a step progress indicator at the top of the checkout view.',
      heuristic_id: 'H1'
    },
    {
      id: 'FND-003',
      title: 'Cancel button color is too prominent',
      severity: 'Low',
      description: 'The cancel button uses the primary brand color, making it look like the primary action.',
      evidence: 'Visual inspection of the modal.',
      recommendation: 'Change the cancel button to a secondary, less saturated color.',
      heuristic_id: 'H8'
    },
    {
      id: 'FND-004',
      title: 'Cannot undo removing item from cart',
      severity: 'Critical',
      description: 'If a user accidentally clicks "Remove", the item disappears immediately without confirmation or undo option.',
      evidence: 'Analytics shows 15% of removed items are added back within 3 seconds.',
      recommendation: 'Provide a toast notification with an standard "Undo" action.',
      heuristic_id: 'H3'
    }
  ]
});

const filters = ref({
  severity: '',
  heuristic: ''
});
const selectedFinding = ref(null);

const fetchReportData = async () => {
  try {
    const response = await fetch('/api/generate-report-json', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(rawPayload.value)
    });
    if (response.ok) {
      reportData.value = await response.json();
      console.log('Report data loaded:', reportData.value);
      if (reportData.value.sections) {
        console.log('Sections received for Sidebar:', reportData.value.sections);
      }
    } else {
      console.error('Failed to load report data:', response.status);
    }
  } catch (err) {
    console.error('Error fetching report:', err);
  }
};

const handleExportPdf = async () => {
  try {
    const response = await fetch('/api/generate-pdf', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(rawPayload.value)
    });
    
    if (response.ok) {
      const blob = await response.blob();
      const url = window.URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', 'heuristic_report.pdf');
      document.body.appendChild(link);
      link.click();
      link.remove();
    } else {
      console.error('PDF generation failed');
    }
  } catch (err) {
    console.error('Error downloading PDF:', err);
  }
};

let observer = null;

onMounted(() => {
  fetchReportData();
  
  // Intersection Observer for Scroll Spy
  const options = {
    root: null,
    rootMargin: '-20% 0px -70% 0px',
    threshold: 0
  };

  observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        activeSection.value = entry.target.id.replace('section-', '');
      }
    });
  }, options);

  // Observe all sections
  setTimeout(() => {
    ['metadata', 'overview', 'summary', 'findings', 'insights', 'appendix'].forEach(id => {
      const el = document.getElementById('section-' + id);
      if (el) observer.observe(el);
    });
  }, 1000);
});

onUnmounted(() => {
  if (observer) observer.disconnect();
});

const filteredFindings = computed(() => {
  if (!reportData.value || !reportData.value.findings) return [];
  return reportData.value.findings.filter(f => {
    const matchSeverity = !filters.value.severity || 
      f.severity.toLowerCase() === filters.value.severity.toLowerCase();
    const matchHeuristic = !filters.value.heuristic || f.heuristic_id === filters.value.heuristic;
    return matchSeverity && matchHeuristic;
  });
});

const updateFilters = (newFilters) => {
  // If clicking the same severity again, clear it (Toggle behavior)
  if (newFilters.severity && filters.value.severity === newFilters.severity) {
    filters.value.severity = '';
  } else {
    filters.value = { ...filters.value, ...newFilters };
  }
};

const openModal = (finding) => {
  selectedFinding.value = finding;
};
</script>

<style>
/* Base Styles */
:root {
  --primary-color: #2563eb;
  --secondary-color: #3b82f6;
  --background-color: #0f172a;
  --surface-color: rgba(30, 41, 59, 0.7);
  --text-primary: #f8fafc;
  --text-secondary: #cbd5e1;
  --border-color: rgba(255, 255, 255, 0.1);
  
  --severity-critical: #ef4444;
  --severity-high: #f97316;
  --severity-medium: #eab308;
  --severity-low: #3b82f6;
}

body {
  margin: 0;
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  background-color: var(--background-color);
  color: var(--text-primary);
  min-height: 100vh;
  scroll-behavior: smooth;
}

.app-layout {
  display: flex;
  gap: 2rem;
  max-width: 1600px;
  margin: 0 auto;
  padding: 1.5rem;
}

.main-container {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 2rem;
  min-width: 0; /* Prevents overflow in flex items */
}

@media (max-width: 1024px) {
  .app-layout {
    flex-direction: column;
    padding: 1rem;
  }
}

.content-wrapper {
  display: flex;
  flex-direction: column;
  gap: 3rem;
}

.section-title {
  font-size: 1.75rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  background: linear-gradient(135deg, #fff 0%, #94a3b8 100%);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}

.findings-grid {
  margin-top: 1rem;
}

.findings-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 1.5rem;
}

.empty-state {
  text-align: center;
  padding: 4rem;
  background: var(--surface-color);
  border-radius: 16px;
  border: 1px solid var(--border-color);
  backdrop-filter: blur(10px);
  color: var(--text-secondary);
  font-size: 1.1rem;
}

/* Glassmorphism Helper */
.glass-panel {
  background: var(--surface-color);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Transition Group Animations */
.fade-slide-enter-active,
.fade-slide-leave-active,
.fade-slide-move {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(30px) scale(0.95);
}

.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-30px) scale(0.95);
}

.fade-slide-leave-active {
  position: absolute;
}
</style>
