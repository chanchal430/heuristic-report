<template>
  <header class="report-header glass-panel" v-if="reportData && reportData.metadata">
    <div class="header-content">
      <div class="title-section">
        <h1 class="project-title">{{ reportData.metadata.project_name }}</h1>
        <p class="subtitle">Heuristic Evaluation Report v{{ reportData.metadata.version }}</p>
      </div>
      <div class="meta-details">
        <div class="meta-item">
          <span class="meta-label">Evaluated By</span>
          <span class="meta-value">{{ reportData.metadata.evaluated_by }}</span>
        </div>
        <div class="meta-item">
          <span class="meta-label">Date</span>
          <span class="meta-value">{{ reportData.metadata.date }}</span>
        </div>
        <button class="export-btn" @click="exportPdf">Export PDF Model</button>
      </div>
    </div>
  </header>
</template>

<script setup>
const props = defineProps({
  reportData: Object
});

const emit = defineEmits(['export-pdf']);

const exportPdf = () => {
  emit('export-pdf');
};
</script>

<style scoped>
.report-header {
  padding: 2rem;
  margin-bottom: 2rem;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1.5rem;
}

.title-section h1 {
  margin: 0;
  font-size: 2.25rem;
  font-weight: 700;
  background: linear-gradient(135deg, #f8fafc 0%, #94a3b8 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.subtitle {
  margin: 0.5rem 0 0 0;
  color: var(--text-secondary);
  font-size: 1.1rem;
}

.meta-details {
  display: flex;
  gap: 2rem;
  align-items: center;
}

.meta-item {
  display: flex;
  flex-direction: column;
}

.meta-label {
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--text-secondary);
}

.meta-value {
  font-weight: 600;
  font-size: 1.05rem;
}

.export-btn {
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}

.export-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
}
</style>
