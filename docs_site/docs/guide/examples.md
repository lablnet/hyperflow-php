# Examples

HyperFlow comes with multiple real-world examples showing standard configurations, local improvement loops, or evolutionary setups.

You can run single evaluations to test if an agent behaves well out-of-the-box, or you can invoke evolutionary loops so the agent writes patches to its own logic!

## Example Domains

- **Bash**: Generates terminal commands.
- **Scoring**: Grade student math answers (accept/reject).
- **Calculator**: Solve math problems using a tool.
- **Fact-check**: Classify statements as true/false.
- **Paper Review**: Predict accept/reject for research paper abstracts.
- **Git Evolution**: A comprehensive standard flow utilizing patches across an isolated git structure branch.

## Running the Examples

### Evaluate Single Run

Execute `run.php` to evaluate the current solver logic strictly once.

```bash
cd examples/bash && php run.php
cd examples/factcheck && php run.php
cd examples/paper_review && php run.php
```

### Run Evolutionary Self-Improvement

Execute `run.php evolve` (or via specific script parameters depending on the example) to trigger the evolutionary loop.

```bash
cd examples/bash && php run.php evolve
cd examples/factcheck && php run.php evolve
cd examples/scoring && php run.php
cd examples/calculator && php run.php
cd examples/git_evolution && php run.php
```

For the git-based evolution:
```bash
cd examples/git_evolution && php run.php        # 2 generations
cd examples/git_evolution && php run.php 5      # 5 generations
cd examples/git_evolution && php run.php --reset # Start over
```
