<template>
  <div class="quick-overview" v-if="data && data.summary">
    <div class="stat-card glass-panel score-card">
      <div class="stat-label">Usability Score</div>
      <div class="stat-value highlight" :class="scoreClass">{{ data.summary.usability_score }}</div>
    </div>
    
    <div class="stat-card glass-panel">
      <div class="stat-label">Total Issues</div>
      <div class="stat-value">{{ data.summary.total_issues }}</div>
    </div>
    
    <div class="stat-card glass-panel">
      <div class="stat-label">Critical Issues</div>
      <div class="stat-value text-critical">{{ data.summary.severity_distribution.critical || 0 }}</div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  data: Object
});

const scoreClass = computed(() => {
  const score = props.data?.summary?.usability_score || 0;
  if (score >= 90) return 'score-excellent';
  if (score >= 75) return 'score-good';
  if (score >= 60) return 'score-fair';
  return 'score-poor';
});
</script>

<style scoped>
.quick-overview {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
}

.stat-card {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.score-card {
  background: linear-gradient(135deg, rgba(30, 41, 59, 0.9) 0%, rgba(15, 23, 42, 0.9) 100%);
}

.stat-label {
  font-size: 0.9rem;
  color: var(--text-secondary);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin-bottom: 0.5rem;
}

.stat-value {
  font-size: 3rem;
  font-weight: 700;
  line-height: 1;
}

.text-critical { color: var(--severity-critical); }

.score-excellent { color: #10b981; }
.score-good { color: #3b82f6; }
.score-fair { color: #f59e0b; }
.score-poor { color: #ef4444; }
</style>
