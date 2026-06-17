#!/usr/bin/env bash
# Télécharge les épreuves CFEE Sénégal depuis deux sources :
#   - banquedesepreuves.com  (2000-2019 + blanc 2014)
#   - cm2.examen.sn          (2006-2012, ancien programme)
# Les fichiers sont enregistrés dans storage/app/public/epreuves/cfee/
# Usage : bash scripts/download_cfee.sh

set -e

DIR="$(cd "$(dirname "$0")/.." && pwd)"
OUT="$DIR/storage/app/public/epreuves/cfee"
mkdir -p "$OUT"

BASE_BDE="https://www.banquedesepreuves.com/index.php/component/edocman"
SUFFIX="/fdocument?Itemid=9999"

download() {
    local slug="$1"
    local name="$2"
    local url="$3"
    local file="$OUT/$name.pdf"

    if [ -f "$file" ]; then
        echo "  [existe] $name.pdf"
        return
    fi
    echo "  [DL] $name.pdf"
    curl -sL --max-time 30 --retry 2 \
         -A "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36" \
         -o "$file" "$url"
    sleep 1
}

bde() { download "$1" "$1" "$BASE_BDE/$1$SUFFIX"; }
cm2() { download "$1" "$1" "http://www.cm2.examen.sn/$2/${1}.pdf"; }

echo ""
echo "=== ANCIEN PROGRAMME (Calcul / Orthographe / Rédaction / Question de cours) ==="

# Banque des épreuves — ancien programme
bde "cfee-2000-senegal-epreuve-de-calcul"
bde "cfee-2000-senegal-epreuve-d-orthographe"
bde "cfee-2001-senegal-epreuve-de-question-de-cours"
bde "cfee-2001-senegal-epreuve-de-redaction"
bde "cfee-2002-senegal-epreuve-de-question-de-cours"
bde "cfee-2002-senegal-epreuve-de-redaction"
bde "cfee-2003-senegal-epreuve-de-question-de-cours"
bde "cfee-2003-senegal-epreuve-de-redaction"
bde "cfee-2004-senegal-epreuve-de-question-de-cours"
bde "cfee-2005-senegal-epreuve-de-calcul"
bde "cfee-2005-senegal-epreuve-d-orthographe"
bde "cfee-2006-senegal-epreuve-de-calcul"
bde "cfee-2006-senegal-epreuve-d-orthographe"
bde "cfee-2007-senegal-epreuve-de-calcul"
bde "cfee-2007-senegal-epreuve-d-orthographe"
bde "cfee-2008-senegal-epreuve-de-calcul"
bde "cfee-2008-senegal-epreuve-d-orthographe"
bde "cfee-2009-senegal-epreuve-de-calcul"
bde "cfee-2009-orthographe"
bde "cfee-2010-senegal-epreuve-de-calcul"
bde "cfee-2010-senegal-orthographe"
bde "cfee-2010-senegal-epreuve-de-redaction"
bde "cfee-2010-senegal-epreuve-de-question-de-cours"
bde "cfee-2011-senegal-epreuve-de-redaction"
bde "cfee-2011-senegal-epreuve-d-orthographe"
bde "cfee-2012-senegal-epreuve-de-redaction"
bde "cfee-2012-senegal-epreuve-de-question-de-cours"
bde "cfee-2012-senegal-epreuve-de-calcul"

# cm2.examen.sn — source complémentaire (redaction, orthographe, calcul, cours)
echo ""
echo "=== CM2.EXAMEN.SN (source complémentaire) ==="
for year in 2006 2007 2008 2009 2010 2011 2012; do
    cm2 "${year}_redaction"   "redaction"
    cm2 "${year}_orthographe" "orthographe"
    cm2 "${year}_calcul"      "calcul"
    cm2 "${year}_cours"       "cours"
done

echo ""
echo "=== NOUVEAU PROGRAMME 2016-2019 (Contrôle compétence / ressources) ==="

# 2016
bde "epreuve-cfee-2016-decouverte-du-monde-controle-de-la-competence"
bde "epreuve-cfee-2016-education-au-developpement-durable-controle-de-la-competence"
bde "epreuve-cfee-2016-langue-et-communication-controle-de-la-competence"
bde "epreuve-cfee-2016-mathematiques-controle-de-la-competence"

# 2017
bde "epreuve-cfee-2017-developpement-durable-controle-de-la-competence"
bde "epreuve-cfee-2017-developpement-durable-controle-des-ressources"
bde "epreuve-cfee-2017-langue-et-communication-controle-de-la-competence"
bde "epreuve-cfee-2017-mathematiques-controle-de-la-competence"

# 2018
bde "epreuve-cfee-2018-developpement-durable-controle-de-la-competence"
bde "epreuve-cfee-2018-education-artistique-controle-de-la-competence"
bde "epreuve-cfee-2018-langue-et-communication-controle-des-ressources"
bde "epreuve-cfee-2018-mathematiques-controle-des-ressources"

# 2019
bde "epreuve-cfee-2019-decouverte-du-monde-controle-des-ressources"
bde "epreuve-cfee-2019-developpement-durable-controle-des-ressources"
bde "epreuve-cfee-2019-langue-et-communication-controle-de-la-competence"
bde "epreuve-cfee-2019-mathematiques-controle-de-la-competence"

echo ""
echo "=== CFEE BLANC 2014 (épreuves + corrigés) ==="

# Épreuves blanc 2014
bde "cfee-blanc-2014-decouverte-du-monde-controle-des-competences"
bde "cfee-blanc-2014-education-au-developpement-durable-controle-des-ressources"
bde "cfee-blanc-2014-langue-et-communication-controle-des-competences"
bde "cfee-blanc-2014-langue-et-communication-controle-des-ressources"
bde "cfee-blanc-2014-mathematiques-controle-des-competences"

# Corrigés blanc 2014
bde "cfee-blanc-2014-corrige-decouverte-du-monde-controle-des-competences"
bde "cfee-blanc-2014-corrige-education-au-developpement-durable-controle-des-competences"
bde "cfee-blanc-2014-corrige-education-au-developpement-durable-controle-des-ressources"
bde "cfee-blanc-2014-corrige-langue-et-communication-controle-des-competences"
bde "cfee-blanc-2014-corrige-langue-et-communication-controle-des-ressources"
bde "cfee-blanc-2014-corrige-mathematiques-controle-des-ressources"

# Corrigé 2013
bde "corrige-cfee-2013-decouverte-du-monde-controle-des-competences"

echo ""
echo "=== TERMINÉ ==="
echo "Fichiers dans : $OUT"
ls "$OUT" | wc -l | xargs echo "Nombre de PDFs :"
