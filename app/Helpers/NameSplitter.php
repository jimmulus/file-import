<?php

namespace App\Helpers;

class NameSplitter
{
    /**
    * splits single name string into salutation, first, last, suffix
    *
    * @param string $name
    *
    * @return array
    */
    public static function fullName($name)
    {
        $honorifics = 'Prof. Mr. Mister Mrs. Misses Ms. Miss Mademoiselle Mlle Madam Fräulein Justice Sir. Dr. Lady Lord';
        $suffixes = 'DDS DVM Sr. Snr. Jr. Jnr. I II III IV V PhD PhyD Ph.D. AB A.B. BA B.A. BE B.E. B.F.A. BS B.S. B.Sc. MS M.S. M.Sc. MFA M.F.A. MBA M.B.A. JD J.D. MD M.D. DO D.O. DC D.C. EdD Ed.D. D.Phil. DBA D.B.A. LLB L.L.B. LLM L.L.M. LLD L.L.D. CCNA OBE MMFT DMFT MSC MSW DSW MAPC MSEd LPsy LMFT LCSW LMHC LCMHC CMHC LMSW LPCC LPC LCPC LPC-S LCAT';

        $results = array();

        $r = explode(' ', $name);
        $size = count($r);

        // Create first_name and check first is in array $honorifics, assume salutation if so
        if (stripos($honorifics, $r[0]) === false) {
            $results['salutation'] = '';
            $results['first_name'] = $r[0];
        } else {
            $results['salutation'] = $r[0];
            $results['first_name'] = $r[1];
        }

        // Check the last part is in array $suffixes, assume suffix if so
        if (stripos($suffixes, $r[$size -1]) === false) {
            $results['suffix'] = '';
        } else {
            $results['suffix'] = $r[$size - 1];
        }

        //combine remains into last name
        $start = ($results['salutation']) ? 2 : 1;
        $end = ($results['suffix']) ? $size - 2 : $size - 1;

        $last = '';
        for ($i = $start; $i <= $end; $i++) {
            $last .= ' '.$r[$i];
        }
        $results['last_name'] = trim($last);

        return $results;
    }
}
