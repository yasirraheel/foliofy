<?php

use App\Support\PortfolioDataStore;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('portfolio_meta')) {
            return;
        }

        $portfolioDataStore = app(PortfolioDataStore::class);

        if ($portfolioDataStore->full() === []) {
            return;
        }

        $portfolioDataStore->save([
            'about' => [
                'text1' => 'I am a retired Pakistan Army professional with service from <strong>28 Apr 2003 to 28 Apr 2026</strong>, including instructor duties, leadership exposure, operational responsibility, project management responsibilities, and structured performance-focused work. My background includes weapons training instruction, instructor-related experience at <strong>SI&T Quetta</strong>, and service as an instructor for the <strong>Kingdom of Saudi Arabia Army</strong> in Weapons and Low Intensity Conflict Training.',
                'text2' => 'My primary career direction is <strong>Networking</strong>. I am currently preparing for <strong>CCNA</strong> and building strong practical lab experience in <strong>Packet Tracer</strong>, while also bringing supporting skills in web development, Android development, and AI-assisted project building. I also hold a <strong>Recognized Instructor Certificate</strong> and a <strong>Junior Leadership and Tactical Supervision Certificate</strong> from Hazara University of Pakistan, Mansehra, and I am open to roles in Pakistan, UAE, KSA, and Europe.',
            ],
            'skills' => [
                'professionalStrengths' => [
                    ['name' => 'Leadership', 'iconClass' => 'fas fa-user-shield', 'iconColor' => '#0f172a', 'level' => 100],
                    ['name' => 'Training and Instruction', 'iconClass' => 'fas fa-chalkboard-user', 'iconColor' => '#0891b2', 'level' => 100],
                    ['name' => 'Tactical Supervision', 'iconClass' => 'fas fa-binoculars', 'iconColor' => '#2563eb', 'level' => 100],
                    ['name' => 'Discipline', 'iconClass' => 'fas fa-medal', 'iconColor' => '#dc2626', 'level' => 100],
                    ['name' => 'Team Coordination', 'iconClass' => 'fas fa-users', 'iconColor' => '#2563eb', 'level' => 100],
                    ['name' => 'Operations Support', 'iconClass' => 'fas fa-compass-drafting', 'iconColor' => '#475569', 'level' => 100],
                    ['name' => 'Structured Execution', 'iconClass' => 'fas fa-list-ol', 'iconColor' => '#1d4ed8', 'level' => 100],
                    ['name' => 'Attention to Detail', 'iconClass' => 'fas fa-magnifying-glass', 'iconColor' => '#0f766e', 'level' => 100],
                    ['name' => 'Adaptability', 'iconClass' => 'fas fa-arrows-rotate', 'iconColor' => '#7c2d12', 'level' => 100],
                ],
            ],
            'experience' => [
                [
                    'title' => 'Havildar (Sergeant)',
                    'company' => 'Pakistan Army',
                    'period' => '28 Apr 2003 - 28 Apr 2026',
                    'location' => 'Pakistan',
                    'desc' => 'Completed more than two decades of disciplined military service with a strong service record across training, supervision, operational support, and performance-focused environments. Responsibilities included core soldier duties, work in operational areas, participation in live operations during the War on Terror, and project management-related responsibilities requiring accountability, consistency, and performance under pressure.',
                    'tags' => ['Leadership', 'Discipline', 'Operations Support', 'Responsibility'],
                    'iconClass' => 'fas fa-shield',
                ],
                [
                    'title' => 'Weapons Training Instructor',
                    'company' => 'Pakistan Army / SI&T Quetta',
                    'period' => 'During military service',
                    'location' => 'Quetta, Pakistan',
                    'desc' => 'Served in instructor-focused roles with responsibility for structured training delivery, technical instruction, tactical supervision, evaluation, and disciplined knowledge transfer. Worked in training and performance-focused environments and earned recognition for professional excellence, instruction, and service quality.',
                    'tags' => ['Training and Instruction', 'Tactical Supervision', 'Performance Standards', 'Attention to Detail'],
                    'iconClass' => 'fas fa-chalkboard-user',
                ],
                [
                    'title' => 'Instructor Assignment - Kingdom of Saudi Arabia Army',
                    'company' => 'Pakistan Army / KSA Army Training Support',
                    'period' => 'During military service',
                    'location' => 'Kingdom of Saudi Arabia',
                    'desc' => 'Served as an instructor for the Kingdom of Saudi Arabia Army in Weapons and Low Intensity Conflict Training. This role reflects international instruction exposure, structured supervision, team coordination, adaptability, and the ability to transfer technical knowledge in disciplined operational settings.',
                    'tags' => ['International Instruction', 'Low Intensity Conflict Training', 'Team Coordination', 'Adaptability'],
                    'iconClass' => 'fas fa-globe',
                ],
                [
                    'title' => 'Networking Track - CCNA Under Preparation',
                    'company' => 'Packet Tracer Lab Practice',
                    'period' => 'Current focus',
                    'location' => 'Bahawalpur, Punjab, Pakistan',
                    'desc' => 'Actively preparing for CCNA with hands-on Packet Tracer lab work covering IP addressing, subnetting, VLANs, inter-VLAN routing, static routing, OSPF, DHCP, NAT, ACLs, STP, EtherChannel, basic device security, and troubleshooting.',
                    'tags' => ['CCNA in Progress', 'Packet Tracer', 'Routing', 'Troubleshooting'],
                    'iconClass' => 'fas fa-network-wired',
                ],
            ],
            'achievements' => [
                [
                    'title' => 'JNC Quetta',
                    'subtitle' => 'AX2',
                    'period' => '2018',
                    'highlight' => '2nd Position',
                    'description' => 'Completed the course with second position and received recognition for performance.',
                ],
                [
                    'title' => 'JLC at JLA Shinkiari Academy',
                    'subtitle' => 'AX2',
                    'period' => '2019',
                    'highlight' => '3rd Position',
                    'description' => 'Ranked third and earned position-based recognition during course completion.',
                ],
                [
                    'title' => 'Sniper Course',
                    'subtitle' => 'Qualified',
                    'period' => '2009',
                    'highlight' => 'Qualified',
                    'description' => 'Successfully completed the course and met qualification standards.',
                ],
                [
                    'title' => 'Unarmed Combat Course',
                    'subtitle' => 'Military Training',
                    'period' => '2010',
                    'highlight' => 'Completed',
                    'description' => 'Completed formal close-combat training as part of military professional development.',
                ],
                [
                    'title' => 'Desert Warfare Course at ADWS Chor Sindh',
                    'subtitle' => 'AX2 Grade',
                    'period' => '2012',
                    'highlight' => '2nd Position',
                    'description' => 'Completed the course with second position and earned formal recognition for performance.',
                ],
                [
                    'title' => 'Position Certificates in Multiple Courses',
                    'subtitle' => 'Professional Military Training',
                    'period' => '',
                    'highlight' => 'Repeated Recognition',
                    'description' => 'Received position certificates across multiple courses in recognition of consistent course performance.',
                ],
                [
                    'title' => 'COAS Commendation Cards',
                    'subtitle' => 'Recognition for Service, Instruction, and Course Performance',
                    'period' => '',
                    'highlight' => 'Awarded by Two Army Chiefs',
                    'description' => 'Received COAS Commendation Cards from General Qamar Javed Bajwa and Field Marshal Asim Munir in recognition of professional excellence, instruction, and service quality.',
                ],
            ],
            'education' => [
                [
                    'title' => 'BS IT',
                    'subtitle' => 'Education',
                    'status' => 'In Progress',
                    'description' => 'Formal information technology studies currently in progress.',
                ],
                [
                    'title' => 'CCNA',
                    'subtitle' => 'Certification Track',
                    'status' => 'In Preparation',
                    'description' => 'Currently preparing for CCNA with practical Packet Tracer labs and networking fundamentals.',
                ],
                [
                    'title' => 'Recognized Instructor Certificate',
                    'subtitle' => 'Professional Credential',
                    'status' => 'Completed',
                    'description' => 'Recognized instructor credential supporting formal training, instruction, and structured supervision experience.',
                ],
                [
                    'title' => 'Junior Leadership and Tactical Supervision Certificate',
                    'subtitle' => 'Hazara University of Pakistan, Mansehra',
                    'status' => 'Completed',
                    'description' => 'Professional credential focused on junior leadership and tactical supervision.',
                ],
            ],
        ]);
    }

    public function down(): void
    {
        // This migration updates live profile content in place and is intentionally not reversed.
    }
};
