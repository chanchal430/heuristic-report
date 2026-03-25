<template>
  <div class="finding-card glass-panel" @click="$emit('view-details', finding)">
    <div class="severity-badge" :class="severityClass">
      {{ finding.severity.toUpperCase() }}
    </div>
    <div class="card-content">
      <div class="heuristic-chip" :title="finding.heuristic_name">
        {{ finding.heuristic_id }}
      </div>
      <h3 class="issue-title">{{ finding.title }}</h3>
      <p class="issue-desc">{{ truncatedDescription }}</p>
    </div>
    <div class="card-footer">
      <span class="view-more">View Details &rarr;</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  finding: Object
});

defineEmits(['view-details']);

const severityClass = computed(() => `badge-${props.finding.severity.toLowerCase()}`);

const truncatedDescription = computed(() => {
  const desc = props.finding.description;
  return desc.length > 100 ? desc.substring(0, 97) + '...' : desc;
});
</script>

<style scoped>
.finding-card {
  display: flex;
  flex-direction: column;
  padding: 1.5rem;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s, background-color 0.2s;
  position: relative;
  overflow: hidden;
}

.finding-card:hover {
  transform: translateY(-4px) scale(1.01);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  background-color: rgba(30, 41, 59, 0.85);
}

.severity-badge {
  position: absolute;
  top: 0;
  right: 0;
  padding: 0.25rem 1rem;
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.1em;
  border-bottom-left-radius: 12px;
}

.badge-critical { background: var(--severity-critical); color: white; }
.badge-high { background: var(--severity-high); color: white; }
.badge-medium { background: var(--severity-medium); color: #1e293b; }
.badge-low { background: var(--severity-low); color: white; }

.heuristic-chip {
  display: inline-block;
  background: rgba(255, 255, 255, 0.1);
  padding: 0.2rem 0.6rem;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--text-secondary);
  margin-bottom: 0.75rem;
}

.issue-title {
  font-size: 1.25rem;
  margin: 0 0 0.5rem 0;
  color: var(--text-primary);
  line-height: 1.4;
}

.issue-desc {
  font-size: 0.95rem;
  color: var(--text-secondary);
  line-height: 1.6;
  flex-grow: 1;
  margin: 0 0 1rem 0;
}

.card-footer {
  margin-top: auto;
  padding-top: 1rem;
  border-top: 1px solid var(--border-color);
  display: flex;
  justify-content: flex-end;
}

.view-more {
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--primary-color);
  transition: color 0.2s;
}

.finding-card:hover .view-more {
  color: var(--secondary-color);
}
</style>
