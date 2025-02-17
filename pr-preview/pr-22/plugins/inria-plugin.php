<?php
/*
Plugin Name: INRIA plugin new
Version: 0.1
Plugin URI: http://wiki.inria.fr/seism
Description: Add shortcodes to insert HAL publications, BASTRI team presentation and RAWEB activity report (the right way).
Author: Ludovic B. - rewritten by PSiLo (based on inline php plugin, thx to the author and to benj)
Author URI:
*/
/**
 * TODO : RÃ©Ã©crire la partie du plugin concernant HAL afin de se passer de la fonction exec_php et du callback qui l'appelle.
 */
/**
* FUNCTION NAME : epi_code_search
* DESCRIPTION : Returns the parent key value of a value from an array (yes it's a bit tricky)
*/

function epi_code_search($JSON_array, $epi_name)
{
    foreach($JSON_array as $key => $value)
    {
        if($value['sigle'] == $epi_name)
            return $key;
    }
    return false;
}
/**
* FUNCTION NAME : exec_php
* DESCRIPTION : Weird function that executes PHP code inside PHP code.
*/
function exec_php($matches){
    try
    {
        eval('ob_start();'.$matches[1].'$inline_execute_output = ob_get_contents();ob_end_clean();');
    }
    catch(Exception $e)
    {
    }
    return $inline_execute_output;
}
/**
* FUNCTION NAME : inria_insert
* DESCRIPTION : Plugin main function
*/
function inria_insert($content)
{
        // On dÃ©tecte la prÃ©sence des balises et le nom de la team demandÃ©
    // Les rÃ©sultats sont classÃ©s de telle faÃ§on que $matches[0] contient la premiÃ¨re sÃ©rie de rÃ©sultats, $matches[1] contient la deuxiÃ¨me, etc.
    // PHP manual : http://www.php.net/manual/fr/function.preg-match-all.php
        preg_match_all('#\[(HAL|TED|BASTRI|RAWEB)(EN|FR)?\](.*)\[/(HAL|TED|BASTRI|RAWEB)(EN|FR)?\]#', $content, $result, PREG_SET_ORDER);
        // On effectue les opÃ©rations en fonction du "service" demandÃ© (HAL, BASTRI ou RAWEB)
        foreach($result as $matches)
    {
                $epi_name = $matches[3];    // Le nom de l'EPI demandÃ©

        //----------------------------- HAL ------------------------------------
        if($matches[1] == 'HAL' && $matches[4] == 'HAL')
        {
            // We replace HAL URLs by a call to PHP function file+echo. Those URLs must begin with the address of the HAL server.
            // This avoid malicious users to include external content in our network. We change HALPLUGIN in other word, this avoid malicious users to find the balise that will execute PHP.
            $content = preg_replace('/\[HAL\]http:\/\/haltools.inrialpes.fr((.|\n)*?)\[\/HAL\]/',"[TOTO724]\$url=\"https://haltools.inria.fr/$1\";\$url_sans_espace=str_replace(' ', '',\$url);\$url_propre=str_replace('&amp;', '&',\$url_sans_espace);\$f_url=file(\$url_propre);foreach(\$f_url as \$i){echo preg_replace('/\n/',' ',\$i);}[/TOTO724]", $content);     //l'url passe par 2 fonctions st_replace qui agissent comme un filtre pour supprimer les espaces et remplacer &amp; par & ...
                    $content = preg_replace('/\[HAL\]http:\/\/haltools.inria.fr((.|\n)*?)\[\/HAL\]/',"[TOTO724]\$url=\"https://haltools.inria.fr/$1\";\$url_sans_espace=str_replace(' ', '',\$url);\$url_propre=str_replace('&amp;', '&',\$url_sans_espace);\$f_url=file(\$url_propre);foreach(\$f_url as \$i){echo preg_replace('/\n/',' ',\$i);}[/TOTO724]", $content);         //meme chose que la ligne du dessus pour l'adresse http:\\haltools.inria.fr
            // there is a problem with the relative path of the CSS in HAL URLs. We have to replace the relativ path by the absolute path.
            $content = preg_replace('/css=\.\.(.)*?/','css=https://haltools.inria.fr/$1', $content);
        }

                //----------------------------- TED ------------------------------------
        else if($matches[1] == 'TED' && $matches[4] == 'TED')
        {
                        $current_date = date_create();          //CrÃ©ation d'une date qui est la date actuelle
                        $year = $current_date->format('Y');             //La date actuelle en annÃ©e pour que le lien ciblÃ© du script soit mis Ã  jour en fonction de l'annÃ©e actuelle
                        $lov28 = 'all'; $lov6 ='all';

                        if($matches[2] == '' && $matches[5] == '')              //TED par dÃ©faut langue FR
                        {
                                $content = preg_replace('/\[TED\](.*)\[\/TED\]/',"<script language=\"javascript\"> mr_ted('https://emea3.recruitmentplatform.com/syndicated/lay/laydisplay.cfm?component=lay2012166_lst400a&amp;ID=PGTFK026203F3VBQB6G68LONZ&amp;page=details.html&amp;pages=index.html&amp;lg=FR&amp;mask=campaign&amp;keywords=\"$1\"&amp;LOV5=all&amp;LOV2=All&amp;LOV6=$lov6&amp;LOV28=$lov28&amp;ContractType=All&amp;statlog=1','lay2012166_lst400a','details.html','index.html','pages','institut/recrutement-metiers/offres/theses/campagne-$year') </script> ", $content);
                        }

                        else if($matches[2] =='EN' && $matches[5] =='EN')       //TEDEN langue EN
                        {
                                $content = preg_replace('/\[TEDEN\](.*)\[\/TEDEN\]/',"<script language=\"javascript\"> mr_ted('https://emea3.recruitmentplatform.com/syndicated/lay/laydisplay.cfm?component=lay2012166_lst400a&amp;ID=PGTFK026203F3VBQB6G68LONZ&amp;page=details.html&amp;pages=index.html&amp;lg=EN&amp;mask=campaign&amp;keywords=\"$1\"&amp;LOV5=all&amp;LOV2=All&amp;LOV6=$lov6&amp;LOV28=$lov28&amp;ContractType=All&amp;statlog=1','lay2012166_lst400a','details.html','index.html','pages','institut/recrutement-metiers/offres/theses/campagne-$year') </script> ", $content);
                        }
                }

           // ------------------------------ BASTRI ------------------------------
        else if($matches[1] == 'BASTRI' && $matches[4] == 'BASTRI')
        {
            // we catch BASTRI shortcodes and replace the name of the team by PHP code
            // this code gets content from a remote JSON file and copies it in the plugin folder everyday
            // the JSON code is converted to a PHP array from which the required data are extracted
            $file_JSON = "/var/www/team/wordpress/wp-content/plugins/inria-plugin/equipes_actives.json";
            $file_last_copy = "/var/www/team/wordpress/wp-content/plugins/inria-plugin/last-copy";
                        $date_today = date_create();
            $date_last_copy = date_create(file_get_contents($file_last_copy));    // date de la derniÃ¨re copie du fichier JSON
            $interval = date_diff($date_today, $date_last_copy);
                        // Si la diffÃ©rence de jours entre maintenant et la derniÃ¨re copie du fichier est diffÃ©rente de zÃ©ro
            if($interval->format('%a') != 0)
            {
                // On rÃ©cupÃ¨re le contenu distant et on l'Ã©crit dans le fichier
                $handle = fopen($file_JSON, "w");
                fwrite($handle, file_get_contents('https://bastri.inria.fr/equipes_actives.json'));
                fclose($handle);

                // On met Ã  jour la date de derniÃ¨re copie du fichier JSON
                $handle = fopen($file_last_copy, "w");
                fwrite($handle, $date_today->format('Y-m-d'));
                fclose($handle);
            }
                        $epi = json_decode(file_get_contents($file_JSON), true);    // On rÃ©cupÃ¨re un Array associatif contenant le JSON
                        $epi_code = epi_code_search($epi, strtoupper($epi_name));    // Retourne le code de l'EPI (nÃ©cessaire pour parcourir l'array)
                        if($matches[2] == 'FR' && $matches[5] == 'FR')
            {
                $presentation_FR = $epi[$epi_code]['project_presentation']['fr'];               //variable prend le contenu du tableau
                $research_FR = $epi[$epi_code]['research_themes']['fr'];
                $relations_FR = $epi[$epi_code]['international_industrial_relations']['fr'];
                                //Conditions pour chaque table si une table contient des caractÃ¨res alors le titre correspondant Ã  cette table s'affiche, sinon le titre contiendra un champs vide
                                if(preg_match('`[a-zA-Z0-9]`',$presentation_FR))                //Recherche Si la variable $presentation_FR contient des caractÃ¨res(donc du texte)
                                {
                                        $titre1_FR="<h1>PrÃ©sentation de l'Ã©quipe</h1>";                    // $titre1_FR a pour valeur le titre PrÃ©sentation de l'Ã©quipe
                                }
                                else
                                {
                                        $titre1_FR=" ";         // titre1_FR a pour valeur un champ vide
                                }
                                if(preg_match('`[a-zA-Z0-9]`',$research_FR))                    //Recherche Si la variable $research_FR contient des caractÃ¨res(donc du texte)
                                {
                                        $titre2_FR="<h1>ThÃ¨mes de recherche</h1>";             //$titre2_FR a pour valeur le titre ThÃ¨mes de recherche
                                }
                                else
                                {
                                        $titre2_FR=" ";
                                }
                                if(preg_match('`[a-zA-Z0-9]`',$relations_FR))
                                {
                                        $titre3_FR="<h1>Relations internationales et industrielles</h1>";
                                }
                                else
                                {
                                        $titre3_FR=" ";
                                }

                                if(preg_match('`[a-zA-Z0-9]`',$epi_code))               //Recherche si $epi code contient des caractÃ¨res
                                {
                                        //Si un axe contient des carctÃ¨res alors il n'y a pas de message d'erreur
                                        if(preg_match('`[a-zA-Z0-9]`',$presentation_FR))
                                        {
                                                $error_FR=' ';
                                        }
                                        else if(preg_match('`[a-zA-Z0-9]`',$research_FR))
                                        {
                                                $error_FR=' ';
                                        }
                                        else if(preg_match('`[a-zA-Z0-9]`',$relations_FR))
                                        {
                                                $error_FR=' ';
                                        }
                                        else    //Sinon associe ce message d'erreur Ã  la variable
                                        {
                                                $error_FR='L\'Ã©quipe n\'a pas renseignÃ© sa fiche projet, le chef de l\'Ã©quipe doit suivre le lien pour le faire <a href=\'https://bastri.inria.fr/FichesProjets/login\'>ici ! </a>';
                                        }
                                }
                                else            //Sinon associe un message d'erreur Ã  la variable
                                {
                                        $error_FR='Cette Ã©quipe n\'a pas Ã©tÃ© trouvÃ© dans la liste des Ã©quipes d\'INRIA, veuillez contacter la direction de recherche ou dÃ©sactiver ce plugin.';         //$error_EN contient le message d'erreur qui explique qu'il faut que son Ã©quipe  soit enregistrÃ© sur BASTRI et affiche un lien de redirection vers le formulaire BASTRI
                                }
                                $content = preg_replace('/\[BASTRIFR\].*\[\/BASTRIFR\]/', "$titre1_FR".$presentation_FR."$titre2_FR".$research_FR."$titre3_FR".$relations_FR."$error_FR", $content);
                        }
                        else if($matches[2] == 'EN' && $matches[5] == 'EN')
            {
                $presentation_EN = $epi[$epi_code]['project_presentation']['en'];
                $research_EN = $epi[$epi_code]['research_themes']['en'];
                $relations_EN = $epi[$epi_code]['international_industrial_relations']['en'];
                //Si une table contient des informations alors le titre correspondant Ã  cette table s'affiche, sinon le titre contiendra un champs vide
                            if(preg_match('`[a-zA-Z0-9]`',$presentation_EN))            //Recherche s'il y a des caractÃ¨res dans $presentation_EN
                                {
                                        $titre1_EN="<h1>Team presentation</h1>";
                                }
                                else
                                {
                                        $titre1_EN=" ";
                                }
                                if(preg_match('`[a-zA-Z0-9]`',$research_EN))            //
                                {
                                        $titre2_EN="<h1>Research themes</h1> ";
                                }
                                else
                                {
                                        $titre2_EN=" ";
                                }
                                if(preg_match('`[a-zA-Z0-9]`',$relations_EN))
                                {
                                        $titre3_EN="<h1>International and industrial relations</h1>";
                                }
                                else
                                {
                                        $titre3_EN=" ";
                                }
                                //Permet d'associer d'associer de dÃ©finir la variable $titre pour l'affichage d'erreur

                                if(preg_match('`[a-zA-Z0-9]`',$epi_code))               //Recherche si $epi_code existe donc si l'Ã©quipe est inscrite sur BASTRI
                                {
                                        if(preg_match('`[a-zA-Z0-9]`',$presentation_EN))
                                        {
                                                $error_EN=' ';
                                        }
                                        else if(preg_match('`[a-zA-Z0-9]`',$research_EN))
                                        {
                                                $error_EN=' ';
                                        }
                                        else if(preg_match('`[a-zA-Z0-9]`',$relations_EN))
                                        {
                                                $error_EN=' ';
                                        }
                                        else
                                        {
                                                $error_EN='The team has not updated his profile project, the team leader must follow the link to do so <a href=\'https://bastri.inria.fr/FichesProjets/login\'>here ! </a>';//$error_EN contient le message d'erreur qui explique que l'Ã©quipe n'a pas rempli sa fiche formulaire
                                        }
                                }
                                else
                                {
                                        $error_EN='This team was not found in the list of teams INRIA, contact the search direction or disable this plugin.';         //$error_EN contient le message d'erreur qui explique qu'il faut que son Ã©quipe  soit enregistrÃ© sur BASTRI et affiche un lien de redirection vers le formulaire BASTRI
                                }

                                $content = preg_replace('/\[BASTRIEN\].*\[\/BASTRIEN\]/', "$titre1_EN".$presentation_EN."$titre2_EN".$research_EN."$titre3_EN".$relations_EN."$error_EN", $content);          //Permet d'afficher des informations d'une Ã©quipe Ã  partir de BASTRI
                        }
        }

        // ------------------ RAWEB ------------------
        else if($matches[1] == 'RAWEB' && $matches[4] == 'RAWEB')
        {
            $epi_name = strtolower($epi_name);
            $current_date = date_create();
            $year = $current_date->format('Y');

                        //Cherche le dernier rapport d'activitÃ© (5ans maxi) et l'affiche
                        for($i = 1; $i <5; $i++)
            {
                $url=file('http://raweb.inria.fr/rapportsactivite/RA'.($year - $i).'/'.$epi_name.'/'.$epi_name.'.xml');
                                foreach($url as $b);            //Stock le contenu de la variable URL dans b
                                if(preg_match('`[a-zA-Z0-9]`',$b))              //Si la variable b contient des caractÃ¨res
                {
                    $date_recente = ($year - $i);
                                        break;          //if s'arrette
                }
                }
                        $xml_file_url = 'http://raweb.inria.fr/rapportsactivite/RA'.$date_recente.'/'.$epi_name.'/'.$epi_name.'.xml';
                // On ouvre le fichier
            $xml = file_get_contents($xml_file_url);
                        // On isole le contenu entre 2 balises <presentation>
                preg_match('#<presentation[^>]*>(.+?)</presentation>#ims', $xml, $hal);        // VÃ©rifier la syntaxe

                        $HAL_presentation1 = $hal[1];
                        //Permet d'Ã©viter l'affichage du titre Overall objectives plusieurs fois
                        $HAL_presentation1 = preg_replace("#<bodyTitle>Overall Objectives</bodyTitle>[\n \t]*<subsection(.*)>#ims","<subsection.$1>", $HAL_presentation1);
                        //Remplace le contenu des balises par des balises html
                        $HAL_presentation1 = preg_replace("#<bodyTitle[^>]*>(.+?)</bodyTitle>#ims", "<h3>$1</h3>", $HAL_presentation1);
                        $HAL_presentation1 = preg_replace("#<subsection[^>]*>(?)</subsection>#ims", "<div>$1</div>", $HAL_presentation1);
            $HAL_presentation1 = preg_replace("#<orderedlist[^>]*>(.+?)</orderedlist>#ims", "<ol>$1</ol>", $HAL_presentation1);
            $HAL_presentation1 = preg_replace("#<simplelist[^>]*>(.+?)</simplelist>#ims", "<ul>$1</ul>", $HAL_presentation1);
            // ... Et peut Ãªtre d'autres que je ne connais pas...

            // Liens vers les rapports prÃ©cÃ©dents s'ils existent (on remonte Ã  5 ans maxi)
                for($i = 1; $i < 5; $i++)
            {
                if(file('http://raweb.inria.fr/rapportsactivite/RA'.($year - $i).'/'.$epi_name.'/'.$epi_name.'.pdf'))
                {
                                        $HAL_presentation3 .= '<li>'.($year - $i).' : <a href="http://raweb.inria.fr/rapportsactivite/RA'.($year - $i).'/'.$epi_name.'/'.$epi_name.'.pdf">PDF</a> - <a href="http://raweb.inria.fr/rapportsactivite/RA'.($year - $i).'/'.$epi_name.'/">HTML</a></li>';
                }
                }
            $HAL_presentation3 .= '</ul>';
                        if(preg_match('`[a-zA-Z0-9]`',$HAL_presentation1))  //condition qui va permettre l'affichage du message d'erreur "Aucun rapport d\'activitÃ© n'a Ã©tÃ© trouvÃ© pour cette Ã©quipe" uniquement si l'Ã©quipe n'a pas de rapport d'activitÃ©
                        {
                                $HAL_presentation2="<h3>Last activity report : $date_recente</h3>";          //$HAL_presentation2 contient cette chaine de caractÃ¨re
                        }
                        else
                        {
                                $HAL_presentation2="No reports of activity was found for this team.";        //$HAL_presentation2 contient cette chaine de caractÃ¨re
                        }

                        echo $HAL_presentation1.$HAL_presentation2.$HAL_presentation3;
                        $content = preg_replace('#\[RAWEB\].*\[/RAWEB\]#i', '', $content);
        }
        // END IF-ELSEIF
    } // END FOREACH
        // And finally we have to replace calls to the file_get_contents function by the real PHP code execution.
    $content = preg_replace_callback('/\[TOTO724\]((.|\n)*?)\[\/TOTO724\]/', 'exec_php', $content);
    return $content;
}
add_filter('the_content', 'inria_insert', 0);
?>
