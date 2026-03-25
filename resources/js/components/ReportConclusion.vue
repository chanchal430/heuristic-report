<template>
  <div class="report-conclusion glass-panel">
    <h2>Evaluator Insights</h2>
    <div class="conclusion-content">
      <p>
        The Heuristic Evaluation indicates that the system fundamentally satisfies most baseline usability standards, yet suffers from critical friction points in the primary user flow (e.g., checkout and error state recovery).
      </p>
      <p>
        Immediate action should be prioritized for <strong>Critical</strong> and <strong>High</strong> severity issues, particularly addressing feedback delay and destructive action prevention. Resolving these will yield the highest return on investment in terms of user satisfaction and task completion rates.
      </p>
    </div>
  </div>
</template>

<style scoped>
.report-conclusion {
  padding: 2rem;
  margin-top: 1rem;
  border-left: 4px solid var(--primary-color);
}

h2 {
  margin-top: 0;
  color: var(--primary-color);
  font-size: 1.5rem;
  margin-bottom: 1rem;
}

.conclusion-content p {
  line-height: 1.8;
  font-size: 1.05rem;
  color: var(--text-secondary);
  margin-bottom: 1rem;
}

.conclusion-content p:last-child {
  margin-bottom: 0;
}

strong {
  color: var(--text-primary);
}
</style>
