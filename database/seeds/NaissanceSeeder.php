<?php

use Illuminate\Database\Seeder;

class NaissanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('naissances')->insert([
            [
                'prenom_enfant' => 'Mienssa Ange Ornella',
                'nom_enfant' => 'KOFFI',
                'numero_acte_naissance' => '551',
                'sexe' => 'Feminin',
                'lieu_naissance_enfant' => 'La Maternité Municipale de Bouaflé',
                'date_naissance_enfant' => '2021-03-21',
                'registre' => 2021,
                'date_dresser' => '2021-03-30',
                'numero_requisition' => null,
                'date_requisition' => null,
                'heure_naissance_enfant' => '04:44',
                'nom_complet_pere' => 'BROU Amenan Fabienne',
                'nom_complet_mere' => 'KOFFI Désiré',
                'date_naissance_pere' => '1998-10-23',
                'date_naissance_mere' => '2000-01-02',
                'numero_piece_identite_pere' => null,
                'numero_piece_identite_mere' => null,
                'adresse_pere' => 'Bouaflé',
                'adresse_mere' => 'Bouaflé',
                'lieu_naissance_pere' => 'Bouaflé',
                'lieu_naissance_mere' => 'Bouaflé',
                'nationalite_mere' => 51,
                'nationalite_pere' => 51,
                'fonction_pere' => 14,
                'fonction_mere' => 5,
                'situation_parents' => 'Mariés',
                'nom_complet_declarant' => 'KOFFI Désiré',
                'date_declaration' => '2021-03-30',
                'date_retrait' => '2021-04-12',
                'contact_declarant' => null,
                'adresse_declarant' => null,
                'date_naissance_declarant' => null,
                'fonction_declarant' => null,
                'nombre_copie' => 1,
                'montant_declaration' =>0,
                'loi' => null,
                'numero_jugement_supletif' => '3468/AF/2009 du 04/12/2009',
                'tribunale' => null,
                'mention_date_deces' => null,
                'mention_date_divorce' => null,
                'mention_date_mariage' => null,
                'mention_lieu_mariage' => null,
                'mention_lieu_deces' => null,
                'mention_conjoint' => null,
                'nom_temoin_1' => null,
                'nom_temoin_2' => null,
                'date_naissance_temoin_1' => null,
                'date_naissance_temoin_2' => null,
                'fonction_temoin_1' => null,
                'fonction_temoin_2' => null,
                'adresse_temoins_1' => null,
                'adresse_temoins_2' => null,
                'dressant' => null,
                'signataire' => null,
                'langue_reception' => null,
                'traducteur' => null,
                'mention_1' => null,
                'mention_2' => null,
                'mention_3' => null,
                'mention_4' => null,
                'mention_5' => null,
                'mention_6' => null,
                'mention_7' => null,
                'mention_8' => null,
                'deleted_at' => null,
                'deleted_by' => null,
                'updated_by' => 11,
                'created_by' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
