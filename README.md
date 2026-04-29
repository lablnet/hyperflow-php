# HyperFlow (PHP)
<p align="center">
  <img src="./assets/logo.png" width="150" alt="HyperFlow Logo" />
</p>

HyperFlow PHP is a fully functional PHP port of the framework for building agents that rewrite and test their own improvements.

Instead of manually retuning prompts and logic after every failure, HyperFlow runs a self-improvement loop where an agent evaluates what happened, edits its own code, tools, and prompts, then tests the new version in a sandbox.

The core idea is simple: do not just rerun the same workflow. Learn from execution and get better over time.

Built natively in PHP 8.1+ utilizing `openai-php/client`. Inspired by [HyperAgents](https://github.com/facebookresearch/HyperAgents) (Meta Research, 2026).

> **⚠️ EXPERIMENTAL**: This project is currently in an experimental phase and is **not recommended for production use**.

## What it does

HyperFlow runs an evolutionary self-improvement loop with two roles:
- **TaskAgent** solves the domain problem
- **MetaAgent** studies evaluation results and improves the system

Each generation:

1. Select a parent generation from the archive
2. MetaAgent reads past evaluation scores and edits the source code
3. Evaluation scripts run in a sandbox to score the new agent
4. Better agents are added back to the archive for future generations

It is **self-referential**: the mechanism that improves the agent is itself part of the editable code.

> [!IMPORTANT]
> This framework is currently in an **Experimental** state. See [Limitations](docs_site/docs/guide/limitations.md) for more information.

The TaskAgent gets better over generations without manual intervention.

## Installation

Install using Composer:

```bash
composer require lablnet/hyperflow-php
```

Or install from source for development:

```bash
git clone https://github.com/lablnet/hyperflow-php.git
cd hyperflow-php

composer install
```

### Requirements

- PHP 8.1+
- Composer
- At least one LLM provider API key (e.g. `OPENAI_API_KEY`)

## Quick Start

```bash
# Set your API key in .env or export it
cp .env.example .env
# Edit .env and set OPENAI_API_KEY

# Run the bash example
cd examples/bash
php run.php
```

## Project Structure

```text
php/
  composer.json
  src/
    Agent/
      AgentOptions.php       # DTO for agent configuration
      AgentSystem.php        # Abstract AgentSystem base class
      Llm.php                # OpenAI client integration
      LlmConfig.php          # Config DTO for the LLM
      LlmWithTools.php       # Synchronous ReAct chat loop
      MetaAgent.php          # MetaAgent (mutation operator)
      TaskAgent.php          # TaskAgent (task solver)
      ToolRegistry.php       # Tool registration
    Contracts/
      BaseChatModel.php      # LLM invocation interface
      BaseTool.php           # Abstract class defining tools
      Message.php            # Base Message class
      AIMessage.php          # Assistant message model
      HumanMessage.php       # User message model
      SystemMessage.php      # System message model
      ToolMessage.php        # Tool execution result model
    Core/
      GenerateLoop.php       # Main evolutionary loop
    Domains/
      Base.php               # Domain interfaces
      Harness.php            # Evaluation harness
    Tools/
      BashTool.php           # Bash shell tool
      EditorTool.php         # File editor tool
    Utils/
      Archive.php            # JSONL archive CRUD
examples/
  bash/                      # Bash command generation
  calculator/                # Buggy tool fix demo
  factcheck/                 # True/false classification
```

## Supported Models

Since this PHP implementation relies primarily on the OpenAI API standard, you can pass any supported model string during configuration:

```php
use HyperFlow\Agent\AgentOptions;

$options = new AgentOptions(model: 'gpt-4o'); // 'gpt-4o', 'gpt-4o-mini', 'o3', etc.
```

## Environment Variables

The framework supports loading environment variables from a `.env` file in the project root.

| Variable | Description |
|----------|-------------|
| `OPENAI_API_KEY` | OpenAI API key |

## Examples

### Single Evaluation / Self-Improvement Loops

Execute the specific scripts depending on the example you want to run. 
The examples demonstrate both strict execution and evolutionary loops.

```bash
cd examples/bash && php run.php
cd examples/factcheck && php run.php
cd examples/paper_review && php run.php
cd examples/calculator && php run.php
```

## License

MIT

## Citation

If you use this framework in your research, please cite the original HyperAgents paper:

```bibtex
@misc{zhang2026hyperagents,
      title={Hyperagents}, 
      author={Jenny Zhang and Bingchen Zhao and Wannan Yang and Jakob Foerster and Jeff Clune and Minqi Jiang and Sam Devlin and Tatiana Shavrina},
      year={2026},
      eprint={2603.19461},
      archivePrefix={arXiv},
      primaryClass={cs.AI},
      url={https://arxiv.org/abs/2603.19461}, 
}
```
