<template>
  <div class="modal-backdrop" @click.self="$emit('close')">
    <div class="modal-content glass-panel">
      <button class="close-btn" @click="$emit('close')">&times;</button>
      
      <div class="modal-header">
        <div class="badge" :class="`badge-${finding.severity.toLowerCase()}`">
          {{ finding.severity.toUpperCase() }} SEVERITY
        </div>
        <h2>{{ finding.title }}</h2>
        <div class="heuristic-info">
          <strong>{{ finding.heuristic_id }}:</strong> {{ finding.heuristic_name }}
        </div>
      </div>
      
      <div class="modal-body">
        <div class="section">
          <h3>Description</h3>
          <p>{{ finding.description }}</p>
        </div>
        
        <div class="section" v-if="finding.evidence">
          <h3>Evidence</h3>
          <div class="evidence-box">
            <p>{{ finding.evidence }}</p>
            <!-- In a real app this might be an image -->
          </div>
        </div>
        
        <div class="section recommendation">
          <h3>Recommendation</h3>
          <p>{{ finding.recommendation }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  finding: Object
});

defineEmits(['close']);
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(15, 23, 42, 0.8);
  backdrop-filter: blur(5px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  padding: 2rem;
}

.modal-content {
  background: #1e293b;
  width: 100%;
  max-width: 800px;
  max-height: 90vh;
  overflow-y: auto;
  border-radius: 16px;
  position: relative;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
  animation: modal-in 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

@keyframes modal-in {
  from { opacity: 0; transform: translateY(20px) scale(0.95); }
  to { opacity: 1; transform: translateY(0) scale(1); }
}

.close-btn {
  position: absolute;
  top: 1.5rem;
  right: 1.5rem;
  background: none;
  border: none;
  color: var(--text-secondary);
  font-size: 2rem;
  line-height: 1;
  cursor: pointer;
  transition: color 0.2s;
}

.close-btn:hover {
  color: white;
}

.modal-header {
  padding: 3rem 3rem 2rem;
  border-bottom: 1px solid var(--border-color);
  background: rgba(15, 23, 42, 0.5);
}

.modal-header h2 {
  font-size: 1.75rem;
  margin: 1rem 0;
  line-height: 1.3;
}

.badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 4px;
  font-size: 0.8rem;
  font-weight: 700;
  letter-spacing: 0.05em;
}

.badge-critical { background: var(--severity-critical); color: white; }
.badge-high { background: var(--severity-high); color: white; }
.badge-medium { background: var(--severity-medium); color: #1e293b; }
.badge-low { background: var(--severity-low); color: white; }

.heuristic-info {
  font-size: 1.05rem;
  color: var(--text-secondary);
}

.modal-body {
  padding: 2rem 3rem 3rem;
}

.section {
  margin-bottom: 2.5rem;
}

.section h3 {
  font-size: 1.1rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--text-secondary);
  margin-bottom: 1rem;
  border-bottom: 1px solid rgba(255,255,255,0.05);
  padding-bottom: 0.5rem;
}

.section p {
  line-height: 1.7;
  font-size: 1.05rem;
}

.evidence-box {
  background: rgba(0, 0, 0, 0.2);
  border: 1px dashed rgba(255, 255, 255, 0.2);
  padding: 1.5rem;
  border-radius: 8px;
  font-family: monospace;
}

.recommendation p {
  font-weight: 500;
  color: #a7f3d0; /* light emerald tint */
}
</style>
