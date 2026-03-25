<template>
  <div class="appendix-data glass-panel" v-if="findings && findings.length">
    <h2>Appendix: Raw Data Table</h2>
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Severity</th>
            <th>Heuristic</th>
            <th>Title</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="finding in findings" :key="finding.id">
            <td class="id-col">{{ finding.id }}</td>
            <td>
              <span class="bagde-mini" :class="`badge-${finding.severity.toLowerCase()}`">
                {{ finding.severity.charAt(0).toUpperCase() }}
              </span>
            </td>
            <td>{{ finding.heuristic_id }}</td>
            <td>{{ finding.title }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
defineProps({
  findings: Array
});
</script>

<style scoped>
.appendix-data {
  padding: 2rem;
  margin-top: 2rem;
}

h2 {
  margin-top: 0;
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: var(--text-secondary);
}

.table-responsive {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
}

th, td {
  padding: 1rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

th {
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--text-secondary);
  font-weight: 600;
}

tr:hover {
  background: rgba(255, 255, 255, 0.02);
}

.id-col {
  font-family: monospace;
  color: var(--text-secondary);
}

.bagde-mini {
  display: inline-block;
  width: 20px;
  height: 20px;
  line-height: 20px;
  text-align: center;
  border-radius: 50%;
  font-size: 0.75rem;
  font-weight: bold;
}

.badge-critical { background: var(--severity-critical); color: white; }
.badge-high { background: var(--severity-high); color: white; }
.badge-medium { background: var(--severity-medium); color: #1e293b; }
.badge-low { background: var(--severity-low); color: white; }
</style>
