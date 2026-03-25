<template>
  <div class="executive-summary glass-panel" v-if="data && data.summary">
    <h2>Executive Summary</h2>
    <div class="charts-container">
      <div class="chart-box">
        <h3>Severity Distribution</h3>
        <div class="bar-chart">
          <div class="bar-row" v-for="(count, severity) in data.summary.severity_distribution" :key="severity">
            <span class="bar-label">{{ severity.charAt(0).toUpperCase() + severity.slice(1) }}</span>
            <div class="bar-wrapper">
              <div class="bar" :class="`bar-${severity}`" :style="{ width: getPercentage(count) + '%' }"></div>
            </div>
            <span class="bar-value">{{ count }}</span>
          </div>
        </div>
      </div>
      <div class="chart-box text-summary">
        <h3>Key Takeaways</h3>
        <p>The system achieved a usability score of <strong>{{ data.summary.usability_score }}/100</strong>.</p>
        <p>There are <strong>{{ data.summary.severity_distribution.critical || 0 }} critical</strong> issues that require immediate attention to prevent task failure.</p>
        <p>Overall, <strong>{{ data.summary.total_issues }}</strong> heuristic violations were recorded during the evaluation.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  data: Object
});

const getPercentage = (count) => {
  const total = props.data.summary.total_issues || 1;
  return Math.max(5, (count / total) * 100);
};
</script>

<style scoped>
.executive-summary {
  padding: 2rem;
}

h2 {
  margin-top: 0;
  margin-bottom: 1.5rem;
  font-size: 1.5rem;
  border-bottom: 1px solid var(--border-color);
  padding-bottom: 0.75rem;
}

.charts-container {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
}

@media (max-width: 768px) {
  .charts-container {
    grid-template-columns: 1fr;
  }
}

.chart-box h3 {
  font-size: 1.1rem;
  color: var(--text-secondary);
  margin-bottom: 1.25rem;
}

.bar-chart {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.bar-row {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.bar-label {
  width: 70px;
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--text-secondary);
}

.bar-wrapper {
  flex-grow: 1;
  background: rgba(255, 255, 255, 0.05);
  height: 12px;
  border-radius: 6px;
  overflow: hidden;
}

.bar {
  height: 100%;
  border-radius: 6px;
  transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
}

.bar-critical { background: var(--severity-critical); }
.bar-high { background: var(--severity-high); }
.bar-medium { background: var(--severity-medium); }
.bar-low { background: var(--severity-low); }

.bar-value {
  width: 25px;
  text-align: right;
  font-weight: 700;
  font-size: 0.9rem;
}

.text-summary {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.text-summary p {
  line-height: 1.6;
  margin-bottom: 1rem;
  font-size: 1.05rem;
}

.text-summary strong {
  color: var(--primary-color);
}
</style>
