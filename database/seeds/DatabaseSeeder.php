<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $sql =
            <<<SQL
INSERT INTO `publications` (`doi`, `publisher`, `title`, `url`, `created_at`, `updated_at`, `meta`)
VALUES
	('10.1037/0003-066x.59.1.29', 'American Psychological Association (APA)', 'How the Mind Hurts and Heals the Body.', 'http://dx.doi.org/10.1037/0003-066x.59.1.29', '2020-06-20 21:34:54', '2020-06-20 21:34:54', '{}'),
	('10.1037/a0030689', 'American Psychological Association (APA)', 'Changing the track in music and misogyny: Listening to music with pro-equality lyrics improves attitudes and behavior toward women.', 'http://dx.doi.org/10.1037/a0030689', '2020-06-21 08:49:38', '2020-06-21 08:49:38', '{}'),
	('10.1037/dev0000412', 'American Psychological Association (APA)', 'A meta-analysis of prosocial media on prosocial behavior, aggression, and empathic concern: A multidimensional approach.', 'http://dx.doi.org/10.1037/dev0000412', '2020-06-21 08:50:24', '2020-06-21 08:50:24', '{}'),
	('10.1037/0022-006x.65.6.928', 'American Psychological Association (APA)', 'Mapping cognitive structures and processes through verbal content: The thought-listing technique.', 'http://dx.doi.org/10.1037/0022-006x.65.6.928', '2020-06-21 08:50:40', '2020-06-21 08:50:40', '{}'),
	('10.1037/a0032899', 'American Psychological Association (APA)', 'The flash-lag effect and related mislocalizations: Findings, properties, and theories.', 'http://dx.doi.org/10.1037/a0032899',  '2020-06-21 08:50:53', '2020-06-21 08:50:53', '{}'),
	('10.1037/0278-6133.25.4.521', 'American Psychological Association (APA)', 'A randomized clinical trial of a population- and transtheoretical model-based stress-management intervention.', 'http://dx.doi.org/10.1037/0278-6133.25.4.521',  '2020-06-21 08:55:40', '2020-06-21 08:55:40', '{}'),
	('10.1037/0021-9010.79.3.364', 'American Psychological Association (APA)', 'Self-efficacy beliefs: Comparison of five measures.', 'http://dx.doi.org/10.1037/0021-9010.79.3.364',  '2020-06-21 08:56:01', '2020-06-21 08:56:01', '{}'),
	('10.1037/e537592011-001', 'American Psychological Association (APA)', 'Cultural Competence for Evaluators: A Guide for Alcohol and Other Drug Abuse Prevention Practitioners Working With Ethnic/Racial Communities', 'http://dx.doi.org/10.1037/e537592011-001', '2020-06-21 08:56:11', '2020-06-21 08:56:11', '{}'),
	('10.1007/s10979-008-9157-5', 'American Psychological Association (APA)', 'Susceptibility of current adaptive behavior measures to feigned deficits.', 'http://dx.doi.org/10.1007/s10979-008-9157-5', '2020-06-21 08:56:22', '2020-06-21 08:56:22', '{}'),
	('10.1037/10332-001', 'American Psychological Association', 'Expectancies and the social–cognitive perspective: Basic principles, processes, and variables.', 'http://dx.doi.org/10.1037/10332-001', '2020-06-21 08:56:34', '2020-06-21 08:56:34', '{}'),
	('10.1037/0278-6133.22.6.579', 'American Psychological Association (APA)', 'Cognitive change 5 years after coronary artery bypass surgery.', 'http://dx.doi.org/10.1037/0278-6133.22.6.579', '2020-06-21 08:56:44', '2020-06-21 08:56:44', '{}'),
	('10.1080/08873267.2002.9977018', 'American Psychological Association (APA)', 'Introduction: Special issue on humanistic approaches to psychological assessment.', 'http://dx.doi.org/10.1080/08873267.2002.9977018', '2020-06-21 08:56:55', '2020-06-21 08:56:55', '{}'),
	('10.1037/a0031800', 'American Psychological Association (APA)', 'Revised NEO Personality Inventory normative data for police officer selection.', 'http://dx.doi.org/10.1037/a0031800', '2020-06-21 08:57:13', '2020-06-21 08:57:13', '{}'),
	('10.1037/10417-000', 'American Psychological Association', 'Stress in policing.', 'http://dx.doi.org/10.1037/10417-000', '2020-06-21 08:57:28', '2020-06-21 08:57:28', '{}'),
	('10.1037/pmu0000155', 'American Psychological Association (APA)', '“Count on me”—The influence of music with prosocial lyrics on cognitive and affective aggression.', 'http://dx.doi.org/10.1037/pmu0000155', '2020-06-21 08:57:40', '2020-06-21 08:57:40', '{}'),
	('10.1037/0033-295x.83.4.257', 'American Psychological Association (APA)', 'Iconic memory.', 'http://dx.doi.org/10.1037/0033-295x.83.4.257', '2020-06-21 08:57:52', '2020-06-21 08:57:52', '{}');

SQL;
        DB::insert($sql);
    }
}
