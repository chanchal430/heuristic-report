<template>
  <!-- Toggle Button for Mobile -->
  <button 
    class="menu-toggle" 
    @click="toggleSidebar" 
    aria-label="Toggle Menu" 
    :class="{ 'is-active': isOpen }"
  >
    <svg v-if="!isOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
    <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
  </button>

  <aside 
    :class="['sidebar-nav', 'glass-panel', { 'is-open': isOpen }]"
  >
    <div class="sidebar-glow"></div>
    <div class="sidebar-content">
      <div class="sidebar-brand">
        <div class="logo-box">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="logo-icon"><path d="M12 2v20M2 12h20M5.07 5.07l13.86 13.86M5.07 18.93L18.93 5.07"/></svg>
        </div>
        <span class="brand-text">Contents</span>
      </div>

      <nav class="nav-links">
        <template v-for="section in sections" :key="section.id">
          <a 
            :href="'#section-' + section.id"
            :class="['nav-item', { 'active': activeSection === section.id || (section.id === 'findings' && isFindingSubActive) }]"
            @click.prevent="scrollToSection(section.id)"
          >
            <div class="icon-wrapper" v-html="getIcon(section.icon)">
            </div>
            <span class="nav-label">{{ section.label }}</span>
            <div v-if="activeSection === section.id" class="active-indicator"></div>
          </a>
          
          <!-- Nested Findings Sub-navigation -->
          <div v-if="section.id === 'findings'" class="sub-nav">
             <div class="sub-nav-group">
               <span class="sub-nav-title">By Severity</span>
               <div class="sub-nav-links">
                 <button v-for="sev in ['Critical', 'High', 'Medium', 'Low']" :key="sev" @click="$emit('filter-severity', sev)" class="sub-nav-item">
                   <span :class="['sev-dot', sev.toLowerCase()]"></span>
                   {{ sev }}
                 </button>
               </div>
             </div>
          </div>
        </template>
      </nav>

      <div class="sidebar-footer">
        <button class="export-pdf-sidebar" @click="$emit('export-pdf')">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
          <span>Generate PDF</span>
        </button>
      </div>
    </div>
  </aside>

  <!-- Overlay for Mobile -->
  <div v-if="isOpen" class="sidebar-overlay" @click="isOpen = false"></div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, defineComponent, h } from 'vue';

const props = defineProps({
  sections: {
    type: Array,
    default: () => []
  },
  activeSection: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['export-pdf', 'navigate', 'filter-severity']);

const isOpen = ref(false);
const isHovered = ref(false);

const isFindingSubActive = computed(() => {
  return props.activeSection === 'findings';
});

const toggleSidebar = () => {
  isOpen.value = !isOpen.value;
};

const scrollToSection = (id) => {
  const element = document.getElementById('section-' + id);
  if (element) {
    element.scrollIntoView({ behavior: 'smooth' });
    emit('navigate', id);
    if (window.innerWidth < 1024) {
      isOpen.value = false;
    }
  }
};

const getIcon = (name) => {
  const icons = {
    info: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>`,
    dashboard: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>`,
    description: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>`,
    list_alt: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"></rect><line x1="7" y1="8" x2="17" y2="8"></line><line x1="7" y1="12" x2="17" y2="12"></line><line x1="7" y1="16" x2="17" y2="16"></line></svg>`,
    insights: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path></svg>`,
    dataset: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v3"></path><path d="M21 12v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3"></path><path d="M3 12h18"></path><path d="M3 8h18"></path></svg>`
  };
  return icons[name] || icons.info;
};
</script>

<style scoped>
.sidebar-nav {
  position: fixed;
  left: 1.5rem;
  top: 1.5rem;
  bottom: 1.5rem;
  width: 280px;
  z-index: 50;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  flex-direction: column;
  height: calc(100vh - 3rem);
  overflow: hidden;
  padding: 1px; /* Space for gradient border */
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.01) 100%);
}

.sidebar-glow {
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle at center, var(--primary-color) 0%, transparent 60%);
  opacity: 0.03;
  pointer-events: none;
  z-index: -1;
  animation: rotateGlow 20s linear infinite;
}

@keyframes rotateGlow {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.sidebar-content {
  display: flex;
  flex-direction: column;
  width: 100%;
  height: 100%;
  padding: 1.5rem;
  box-sizing: border-box;
}

.sidebar-brand {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 2.5rem;
  padding: 0.5rem;
}

.logo-box {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.brand-text {
  font-size: 1.25rem;
  font-weight: 800;
  letter-spacing: -0.02em;
  background: linear-gradient(135deg, #fff 0%, #94a3b8 100%);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}

.nav-links {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.65rem;
  overflow-y: auto;
  overflow-x: hidden;
  padding-right: 6px;
  scrollbar-width: none; /* Hide for Firefox */
  -ms-overflow-style: none; /* Hide for IE/Edge */
  transition: padding 0.3s;
}

/* Custom Scrollbar that only shows on hover */
.nav-links:hover {
  scrollbar-width: thin;
}

.nav-links::-webkit-scrollbar {
  width: 4px;
  display: none; /* Hide for Chrome/Safari */
}

.nav-links:hover::-webkit-scrollbar {
  display: block;
}

.nav-links::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 10px;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 0.85rem 1rem;
  text-decoration: none;
  color: var(--text-secondary);
  border-radius: 12px;
  transition: all 0.2s ease;
  position: relative;
}

.nav-item:hover {
  background: rgba(255, 255, 255, 0.05);
  color: var(--text-primary);
  transform: translateX(4px);
}

.nav-item.active {
  background: rgba(37, 99, 235, 0.15);
  color: #60a5fa;
  font-weight: 600;
  box-shadow: inset 0 0 15px rgba(37, 99, 235, 0.1), 0 0 10px rgba(37, 99, 235, 0.1);
}

.icon-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
}

.nav-icon {
  opacity: 0.7;
  transition: opacity 0.2s;
}

.nav-item:hover .nav-icon,
.nav-item.active .nav-icon {
  opacity: 1;
}

.active-indicator {
  position: absolute;
  left: 0;
  top: 20%;
  bottom: 20%;
  width: 4px;
  background: var(--primary-color);
  border-radius: 0 4px 4px 0;
  box-shadow: 2px 0 8px rgba(37, 99, 235, 0.5);
}

.sub-nav {
  margin-left: 2.5rem;
  padding: 0.5rem 0;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  border-left: 1px solid var(--border-color);
}

.sub-nav-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.sub-nav-title {
  font-size: 0.7rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--text-secondary);
  opacity: 0.6;
  padding-left: 1rem;
}

.sub-nav-links {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.sub-nav-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.4rem 1rem;
  font-size: 0.85rem;
  color: var(--text-secondary);
  background: transparent;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
  text-align: left;
  border-radius: 6px;
}

.sub-nav-item:hover {
  color: var(--text-primary);
  background: rgba(255, 255, 255, 0.03);
}

.sev-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
}

.sev-dot.critical { background: var(--severity-critical); }
.sev-dot.high { background: var(--severity-high); }
.sev-dot.medium { background: var(--severity-medium); }
.sev-dot.low { background: var(--severity-low); }

.sidebar-footer {
  position: sticky;
  margin-top: auto;
  padding-top: 1.5rem;
  border-top: 1px solid var(--border-color);
}

.export-pdf-sidebar {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  padding: 0.85rem;
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
  color: white;
  border: none;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.export-pdf-sidebar:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
}

.menu-toggle {
  display: none;
  position: fixed;
  top: 1.25rem;
  left: 1.25rem;
  z-index: 100;
  background: var(--surface-color);
  backdrop-filter: blur(10px);
  border: 1px solid var(--border-color);
  color: var(--text-primary);
  padding: 0.75rem;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.menu-toggle.is-active {
  left: 230px; /* Moves with the sidebar edge roughly */
}

.sidebar-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  backdrop-filter: blur(4px);
  z-index: 40;
}

/* Responsive Logic */
@media (max-width: 1024px) {
  .sidebar-nav {
    left: -300px;
    top: 0;
    bottom: 0;
    border-radius: 0;
    width: 280px;
  }

  .sidebar-nav.is-open {
    left: 0;
    box-shadow: 20px 0 50px rgba(0, 0, 0, 0.5);
  }

  .menu-toggle {
    display: flex;
    z-index: 60;
  }
  
  .sidebar-overlay {
    display: block;
  }
}

@media (min-width: 1025px) {
  .sidebar-nav {
    position: sticky;
    top: 1.5rem;
    height: calc(100vh - 3rem);
  }
}
</style>
