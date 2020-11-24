SELECT l.locatie, a.product , a.type, v.aantal, f.fabriek
FROM voorraad as v
INNER JOIN locatie as l on v.LocatieID = l.id
INNER JOIN artikel as a on v.ArtikelID = a.id
INNER JOIN fabriek as f on a.FabriekID = f.id