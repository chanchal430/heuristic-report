<template>
  <div class="filters glass-panel">
    <div class="filter-group">
      <label for="severity-filter">Severity</label>
      <select id="severity-filter" v-model="localFilters.severity" @change="emitUpdate">
        <option value="">All Severities</option>
        <option value="critical">Critical</option>
        <option value="high">High</option>
        <option value="medium">Medium</option>
        <option value="low">Low</option>
      </select>
    </div>
    
    <div class="filter-group">
      <label for="heuristic-filter">Heuristic</label>
      <select id="heuristic-filter" v-model="localFilters.heuristic" @change="emitUpdate">
        <option value="">All Heuristics</option>
        <option value="H1">H1: Visibility of system status</option>
        <option value="H2">H2: Match between system and real world</option>
        <option value="H3">H3: User control and freedom</option>
        <option value="H4">H4: Consistency and standards</option>
        <option value="H5">H5: Error prevention</option>
        <option value="H6">H6: Recognition rather than recall</option>
        <option value="H7">H7: Flexibility and efficiency of use</option>
        <option value="H8">H8: Aesthetic and minimalist design</option>
        <option value="H9">H9: Help users recognize, diagnose, and recover from errors</option>
        <option value="H10">H10: Help and documentation</option>
      </select>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  filters: Object
});

const emit = defineEmits(['update:filters']);

const localFilters = ref({ ...props.filters });

const emitUpdate = () => {
  emit('update:filters', localFilters.value);
};
</script>

<style scoped>
.filters {
  display: flex;
  gap: 2rem;
  padding: 1.25rem 2rem;
  align-items: center;
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.filter-group label {
  font-weight: 500;
  color: var(--text-secondary);
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

select {
  background: rgba(15, 23, 42, 0.6);
  border: 1px solid var(--border-color);
  color: var(--text-primary);
  padding: 0.5rem 1rem;
  border-radius: 6px;
  font-family: inherit;
  font-size: 1rem;
  cursor: pointer;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
}

select:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.3);
}

select option {
  background: var(--background-color);
  color: var(--text-primary);
}
</style>
