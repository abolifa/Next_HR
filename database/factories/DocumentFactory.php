<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Document;
use DateMalformedStringException;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    /**
     * @throws DateMalformedStringException
     */
    public function definition(): array
    {
        $paperTypes = [
            'commercial_register',
            'business_license',
            'economic_operator',
            'importers_register',
            'statistical_code',
            'chamber_of_commerce',
            'industrial_register',
            'tax_clearance',
            'social_security_clearance',
            'solidarity',
            'articles_of_association',
            'general_assembly_meeting',
            'founding_contract',
            'amendment_contract'
        ];

        $issueDate = $this->faker->dateTimeBetween('-5 years');
        $expiryDate = (clone $issueDate)->modify('+1 year');

        return [
            'company_id' => Company::factory(),
            'paper_type' => $this->faker->randomElement($paperTypes),
            'document_number' => strtoupper($this->faker->bothify('DOC-####')),
            'attachments' => json_encode([$this->faker->imageUrl(400, 300, 'documents', true)]),
            'issue_date' => $issueDate,
            'expiry_date' => $expiryDate,
        ];
    }
}
