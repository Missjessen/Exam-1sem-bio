Doc. file
sti: pwd
/Applications/XAMPP/xamppfiles/htdocs/Exam-1sem-bio

git:

git add .


git commit -m "Din besked her"
git push 


grep -v "resolved" /Applications/XAMPP/xamppfiles/htdocs/Exam-1sem-bio/logs/errors.log

grep -v "fixed" /Applications/XAMPP/xamppfiles/htdocs/Exam-1sem-bio/logs/errors.log


I Visual Studio Code (VS Code) indikerer de forskellige farver på teksten og ikonerne filernes tilstand i forhold til versionskontrolsystemet, såsom Git. Her er en forklaring på farverne i dit projekt:

Farvekoder og deres Betydninger:
Grøn: Dette betyder, at filen er ny og endnu ikke tilføjet til versionskontrollen (Git).
Gul/Orange: Indikerer, at filen er blevet ændret, men ændringerne er endnu ikke blevet tilføjet (staged) eller committed.
Blå: Filen er blevet ændret og er i staging area, men ændringerne er endnu ikke committed (ikke synlig på dette billede).
Hvid/Grå: Filen er uændret siden den sidste commit (ingen ændringer).
Andre Ikoner og Symboler:
M (Modified): Filen er blevet ændret siden den sidste commit.
U (Untracked): Filen er ny og endnu ikke blevet tilføjet til Git.
Bogmærke: En enkelt hash eller ikon ved siden af filnavnet kan indikere, at filen er "traced" af Git (dvs. ikke ignoreret).
Dette gør det nemt at se, hvilke filer der er nye, hvilke der er ændrede, og hvilke der ikke har nogen ændringer siden den sidste commit. Hvis du holder musen over filens navn, giver VS Code også yderligere information om filens status.

-- Indsæt en standardadmin (Brug password_hash i PHP til at generere hashet kodeord)
-- Eksempel på indsættelse:
--INSERT INTO users (username, email, password, is_validated) 
--VALUES ('testuser', 'testuser@example.com', '$2y$10$dF7jq39V4i0OMuBKQUtfCe8zfJraemow8.sjoRbqzh74Tx8D8Ob7S', 1);