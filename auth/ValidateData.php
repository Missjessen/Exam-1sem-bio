<?php

class ValidateData {
    /**
     * Validerer data baseret på regler.
     *
     * @param array $data Data, der skal valideres.
     * @param array $rules Regler for validering.
     * @throws Exception Hvis en valideringsregel fejler.
     */
    public static function validate($data, $rules) {
        foreach ($rules as $field => $ruleSet) {
            foreach ($ruleSet as $rule) {
                // Krav om værdi
                if ($rule === 'required' && empty($data[$field])) {
                    throw new Exception(ucfirst($field) . " er påkrævet.");
                }

                // Email validering
                if ($rule === 'email' && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Ugyldig email-adresse for $field.");
                }

                // Minimum længde
                if (strpos($rule, 'min:') === 0) {
                    $minLength = intval(explode(':', $rule)[1]);
                    if (!empty($data[$field]) && strlen($data[$field]) < $minLength) {
                        throw new Exception(ucfirst($field) . " skal være mindst $minLength tegn.");
                    }
                }

                // Maksimum længde
                if (strpos($rule, 'max:') === 0) {
                    $maxLength = intval(explode(':', $rule)[1]);
                    if (!empty($data[$field]) && strlen($data[$field]) > $maxLength) {
                        throw new Exception(ucfirst($field) . " må ikke overstige $maxLength tegn.");
                    }
                }
            }
        }
    }
}
