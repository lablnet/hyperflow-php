# Examples

HyperFlow comes with multiple real-world examples showing standard configurations, local improvement loops, or evolutionary setups.

You can run single evaluations to test if an agent behaves well out-of-the-box, or you can invoke evolutionary loops so the agent writes patches to its own logic!

## Example Domains

- **Bash**: Generates terminal commands.
- **Calculator**: Solve math problems using a tool. The MetaAgent fixes bugs in the calculator tool when the score is low.
- **Fact-check**: Classify statements as true/false using an evolutionary loop that improves the system prompts.

## Running the Examples

### Setup

Ensure you have set your `OPENAI_API_KEY` in a `.env` file in the project root.

```bash
cp .env.example .env
# Edit .env and set OPENAI_API_KEY
```

### 1. Calculator (Buggy Tool Fix Demo)

This example demonstrates how a MetaAgent can fix bugs in a tool's source code based on evaluation results.

```bash
cd examples/calculator
php run.php
```

### 2. Bash (Command Generation)

This example evaluates the agent's ability to generate correct Bash commands for given tasks.

```bash
cd examples/bash
php run.php
```

### 3. Fact-check (Evolutionary Prompt Tuning)

This example runs a multi-generation evolutionary loop where the MetaAgent improves the system prompts to achieve higher classification accuracy.

```bash
cd examples/factcheck
php run.php
```
