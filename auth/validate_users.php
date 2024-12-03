 <?php
function validateComment($comment) {
    // Tjek for tom kommentar
    if (empty($comment)) {
        return "Kommentar må ikke være tom.";
    }
    
    // Begrænsning på længde
    if (strlen($comment) > 500) {
        return "Kommentar må ikke overstige 500 tegn.";
    }

    // Fjern HTML-tags og specialtegn for at forhindre XSS
    $comment = strip_tags($comment);
    $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');

    // Profanitetsfilter (simpel ordliste)
    $forbudteOrd = ["forbudtOrd1", "forbudtOrd2"]; // Tilføj ord efter behov
    foreach ($forbudteOrd as $ord) {
        if (stripos($comment, $ord) !== false) {
            return "Din kommentar indeholder upassende sprog.";
        }
    }

    return $comment; // Returner renset kommentar
}
?>
