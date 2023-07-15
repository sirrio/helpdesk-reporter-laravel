<?php

namespace Database\Seeders;

use App\Models\Degree;
use Illuminate\Database\Seeder;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $degrees = ['Angewandte Informatik (B.Sc.)',
            'Advanced Media Technology (M.Eng.)',
            'Angewandte Mathematik (B.Sc.)',
            'Angewandte Mathematik (M.Sc.)',
            'Angewandte Physik (B.Sc.)',
            'Angewandte Physik (M.Sc.)',
            'Bauingenieurwesen (B.Eng.)',
            'Bio- und Umwelttechnik (M.Eng.)',
            'BIS Elektrotechnik (Dipl.-Ing.)',
            'BIS Maschinenbau (B.Eng.)',
            'BIS Product Development & Manufacturing (M.Eng.)',
            'BIS Wirtschaftsingenieurwesen (M.Eng.)',
            'Business Administration (B.A.)',
            'Business and Law in Accounting and Taxation (LL.B.)',
            'Business and Law in Accounting and Taxation (LL.M.)',
            'Controlling and Finance (M.A.)',
            'Digital Business Management (B.Sc.)',
            'Electrical Engineering - Connected Systems (M.Eng.)',
            'Elektro- und Luftfahrttechnik (B.Eng.)',
            'Elektrotechnik (B.Eng.)',
            'Fahrzeugentwickl., Energietech. u. Produktionsplanung (M.Eng.)',
            'Gesundheitökonomie (B.Sc.)',
            'Immobilienmanagement (B.Eng.)',
            'Informatik - Technische Systeme (B.Sc.)',
            'Innenarchitektur (B.A.)',
            'Interdisziplinäre Ingenieurwissenschaften (B.Eng.)',
            'International Management (B.A.)',
            'International Management (M.A.)',
            'Internationales Wirtschaftsingenieurwesen (B.Eng.)',
            'Kooperatives Ingenieurstudium Elektrotechnik (B.Eng.)',
            'Kooperatives Ingenieurstudium Mechatronik (B.Eng.)',
            'Kooperatives Ingenieurstudium Medientechnik (B.Eng.)',
            'Management im Gesundheitswesen (M.A.)',
            'Maschinenbau (B.Eng.)',
            'Media and Communications Technology (M.Eng.)',
            'Media Management (B.Sc.)',
            'Medieninformatik (B.SC.)',
            'Medientechnik (B.Eng.)',
            'Medizintechnik (M.Sc.)',
            'Mobilitätsmanagement (B.Eng.)',
            'Physikalische Technik (B.Sc.)',
            'Sales and Marketing Management (M.A.)',
            'Umwelttechnik (B.Eng.)',
            'Versicherungs- und Finanzwirtschaft (AIS) (B.Sc.)',
            'Versicherungs- und Finanzwirtschaft (B.Sc.)',
            'Versicherungs- und Finanzwirtschaft (M.Sc.)',
            'Wirtschaftsinformatik (B.Sc.)',
            'Wirtschaftsingenieurwesen (B.Eng.)',
            'Keine Angabe',
            'Sonstiges'];

        foreach ($degrees as $degree) {
            Degree::create([
                'name' => $degree,
            ]);
        }
    }
}
