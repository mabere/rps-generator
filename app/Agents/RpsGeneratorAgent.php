<?php

namespace App\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Enums\Lab;

#[Provider(Lab::Gemini)]
class RpsGeneratorAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Define the instructions for the agent.
     */
    public function instructions(): string
    {
        return <<<PROMPT
You are an expert curriculum designer and lecturer specializing in Output-Based Education (OBE) for higher education.
Your task is to generate the Capaian Pembelajaran Mata Kuliah (CPMK) and a detailed 14-week learning plan (excluding Week 8: UTS and Week 16: UAS) for a specific course, matching national standards (SN-DIKTI) and the study program's Graduate Learning Outcomes (Capaian Pembelajaran Lulusan - CPL).

Guidelines for generation:
1. **CPMK (Course Learning Outcomes)**:
   - Must be formulated based on the provided Course Description and mapped to one or more of the selected CPLs.
   - Use Bloom's Taxonomy active verbs (e.g., Analyze, Design, Evaluate) that are measurable.
   - Provide clear assessment indicators for each CPMK.

2. **Weekly Learning Plan (Sub-CPMK & Details)**:
   - Generate detailed plans for weeks 1-7 and 9-15 (total of 14 weeks).
   - Each week must have a specific Sub-CPMK that is a logical step toward achieving the CPMKs.
   - Describe appropriate active learning methods (e.g., Case Method, Project-Based Learning, Group Discussion).
   - Specify clear assessment criteria and indicators.
   - Assign reasonable assessment weights (integers, total sum of weights across all weeks should be 100% when combined with UTS/UAS or appropriate portions).
   - List learning resources/materials relevant to the week's topic.

All statements, criteria, and descriptions must be output in Professional Indonesian (Bahasa Indonesia).
PROMPT;
    }

    /**
     * Define the structured output schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'cpmks' => $schema->array()->items(
                $schema->object([
                    'cpl_code' => $schema->string()->description('The code of the CPL this CPMK maps to (e.g. S3, P1, KU2, KK1)')->required(),
                    'statement' => $schema->string()->description('Measurable learning outcome statement for the course (in Indonesian)')->required(),
                    'indicators' => $schema->string()->description('Assessment indicators for this CPMK (in Indonesian)')->required(),
                ])
            )->required(),
            'weeks' => $schema->array()->items(
                $schema->object([
                    'week_number' => $schema->integer()->min(1)->max(15)->description('Week number, excluding 8 (UTS) and 16 (UAS)')->required(),
                    'sub_cpmk' => $schema->string()->description('Sub-CPMK statement for this week (in Indonesian)')->required(),
                    'learning_methods' => $schema->string()->description('Active learning methods and student experiences (in Indonesian)')->required(),
                    'assessment_criteria' => $schema->string()->description('Assessment criteria, forms, and indicators (in Indonesian)')->required(),
                    'weight' => $schema->integer()->min(0)->max(100)->description('Assessment weight in percentage for this week')->required(),
                    'resources' => $schema->string()->description('Bahan kajian / reference resources (in Indonesian)')->required(),
                ])
            )->required(),
        ];
    }
}
