<?php
/*
*
* CZECH ADMIN HELP LANGUAGE FILE
* 
*/

$txt['help_on_roles'] = "<div class='ui-state-highlight ui-corner-all' style='padding:5px;font-weight:bold;'>
Na této stránce můžete vytvářet a spravovat ROLE.<br />
Role je přiřazena sadě dovolených nebo zakázaných složek.<br />
Jakmile jsou role definovány, lze jím přiřadit UŽIVATELE.
</div>
<div id='accordion'>
    <h3><a href='#'>Přidat novou ROLI</a></h3>
    <div>
        Klikněte na symbol <img src='includes/images/users--plus.png' alt='' />. Objeví se dialog, v němž musíte zadat název této nové ROLE.
    </div>
    
    <h3><a href='#'>Povolit nebo zakázat přístup ke složce</a></h3>
    <div>
        Pro definování přístupových práv můžete použít matici 'Role vs Složky'. Je-li políčko červené, nemá tato role ke složce přístup. Je-li políčko zelené, přístup ke složce tato role má.<br />
        Chcete-li změnit přístupová práva, klikněte na odpovídající políčko v matici.<br/>
        <p style='text-align:center;'>
            <span style='text-align:center;'><img src='includes/images/help/roles_1.png' alt='' /></span>
        </p>
        Na snímku obrazovky vidíte, že složka 'Cleaner' je povolená pro roli 'Dev' ale nikoliv pro roli 'Commercial'.
    </div>
    
    <h3><a href='#'>Manuelně aktualizovat matici</a></h3>
    <div>
        Klikněte na symbol <img src='includes/images/arrow_refresh.png' alt='' />.
    </div>
    
    <h3><a href='#'>Změnit roli</a></h3>
    <div>
        Název role lze změnit bez dopadu na již definované parametry (práva).<br />
        Vyberte roli, kterou chcete změnit a klikněte na symbol <img src='includes/images/ui-tab--pencil.png' alt='' />.<br />
        Objeví se dialog, v němž múžete zadat nový název role.
    </div>
    
    <h3><a href='#'>Odstranit roli</a></h3>
    <div>
        Stávající roli múžete odstranit.<br />
        Vyberte roli, kterou chcete odstranit a klikněte na symbol <img src='includes/images/ui-tab--minus.png' alt='' />.<br />
        Objeví se dialog, v němž je třeba potvrdit, zda chcete odstranit roli.
    </div>";

$txt['help_on_users'] = "<div class='ui-state-highlight ui-corner-all' style='padding:5px;font-weight:bold;'>
Na této stránce můžete vytvářet a spravovat UŽIVATELE.<br />
Uživatelský účet je základem pro autorizovaný přístup k aplikaci cPassMan.<br />
<span class='ui-icon ui-icon-lightbulb' style='float: left;'>&nbsp;</span>1.krokem je stanovení ROLE, kterou tento uživatel má.<br />
<span class='ui-icon ui-icon-lightbulb' style='float: left;'>&nbsp;</span>2.krokem je ůprava přístupových práv ke specifickým složkám (nebo jejich ponechání).
</div>
<div id='accordion'>
    <h3><a href='#'>Přidat nového UŽIVATELE</a></h3>
    <div>
        Klikněte na symbol <img src='includes/images/user--plus.png' alt='' />. Objeví se dialog, v němž musíte zadat:<br />        
        - uživatelské jméno<br />
        - uživatelské heslo (může být vygenerováno a změněno uživatelem při 1.připojení)<br />
        - platnou emailovou adresu<br />
        - zda je uživatel Správcem (plný přístup ke všem funkcím aplikace)<br />
        - zda je uživatel Manažerem (plný přístup k položkám)<br />
        - zda má uživatel mít Osobní Složky  
    </div>
    <h3><a href='#'>Přiřadit UŽIVATELI ROLI</a></h3>
    <div>
        UŽIVATELI múžete přiřadit libovolné množství ROLÍ. Klikněte na symbol <img src='includes/images/cog_edit.png' alt='' />.<br />
        Objeví se dialog, v němž můžete zaškrtnout požadované role.<br /><br />
        Je-li UŽIVATELI přiřazena ROLE, má tento UŽIVATEL přístup ke složkám povoleným pro tuto ROLI a ke složkám zakázaným přístup nemá.<br /><br />
        Přístupová práva daného UŽIVATELE lze upřesnit pomocí políček 'Povolené složky' a 'Zakázané složky'. Tak lze dodatečně povolit či zakázat přístup ke složkám, které pro ROLI nejsou specifikovány.
        <div style='margin:2px Opx 0px 20px;'>
            Např.:
            <p style='margin-left:20px;margin-top: 2px;'>
            - UŽIVATEL1 má ROLI1 a ROLI2. <br />
            - ROLI1 je povolen přístup ke složkám S1 a S2. <br />
            - Složka S1 má 4 podsložky PS1, PS2, PS3 a PS4.<br />
            - Tzn. UŽIVATEL1 má přístup k S1, S2, PS1, PS2, PS3 a PS4.<br />
            - Přístup UŽIVATELE1 k podsložce PS4 lze upravit zákazem na této stránce.
            </p>
        </div>
    </div>
    <h3><a href='#'>Je SPRÁVCE (Všemohoucí)</a></h3>
    <div>
        Uživateli lze udělit práva SPRÁVCE (Všemohoucího). Chcete-li to, zaškrtněte políčko.<br /> 
        SPRÁVCE smí v aplikaci cPassMan provádět cokoli bez omezení. Buďte tedy opatrný/á!!!!
        <p style='text-align:center;'>
        <img src='includes/images/help/users_1.png' alt='' />
        </p>
    </div>
    <h3><a href='#'>Je MANAŽER</a></h3>
    <div>
        Uživateli lze udělit práva MANAŽERA. Chcete-li to, zaškrtněte políčko.<br /> 
        MANAŽER smí měnit a odstrańovat složky a položky - i ty, které sám nevytvořil.<br /> 
        Manažer má přístup pouze ke složkám pro něj povoleným. Pro různá oddělení tedy můžete vytvořit různé správce.    
        <p style='text-align:center;'>
        <img src='includes/images/help/users_2.png' alt='' />
        </p>
    </div>
    <h3><a href='#'>Odstranit UŽIVATELE</a></h3>
    <div>
        Uživatele můžete odstranit. Chcete-li to, klikněte na symbol <img src='includes/images/user--minus.png' alt='' />.
        <p style='text-align:center;'>
        <img src='includes/images/help/users_3.png' alt='' />
        </p>
    </div>
    <h3><a href='#'>Změnit uživatelské heslo</a></h3>
    <div>
        Uživatelské heslo lze změnit. Chcete-li to, klikněte na symbol <img src='includes/images/lock__pencil.png' alt='' />.<br /> 
        Uživatel jej bude muset změnit při svém 1.připojení. 
        <p style='text-align:center;'>
        <img src='includes/images/help/users_4.png' alt='' />
        </p>
    </div>
    <h3><a href='#'>Změnit emailovou adresu uživatele</a></h3>
    <div>
        Emailovou adresu uživatele můžete změnit. Chcete-li to, klikněte na symbol <img src='includes/images/mail--pencil.png' alt='' />.<br />   
        <p style='text-align:center;'>
        <img src='includes/images/help/users_5.png' alt='' />
        </p>
    </div>
</div>
";

$txt['help_on_folders'] = "<div class='ui-state-highlight ui-corner-all' style='padding:5px;font-weight:bold;'>
Na této stránce můžete vytvářet a spravovat SLOŽKY.<br />
Pomocí složek můžete organizovat Vaše položky - podobně jako složky souborů ve Windows.<br />
<span class='ui-icon ui-icon-lightbulb' style='float: left;'>&nbsp;</span>Nejnižší úroveň složky se nazývá KOŘEN (ROOT).<br />
<span class='ui-icon ui-icon-lightbulb' style='float: left;'>&nbsp;</span>Systém složek a podsložek tvoří stromovou strukturu.<br />
<span class='ui-icon ui-icon-lightbulb' style='float: left;'>&nbsp;</span>Každá složka má přiřazenu úroveň ve stromové struktuře.
</div>
<div id='accordion'>
    <h3><a href='#'>Přidat novou SLOŽKU</a></h3>
    <div>
        Klikněte na symbol <img src='includes/images/folder--plus.png' alt='' />. Objeví se dialog, v němž musíte zadat:<br />        
        - název složky<br />
        - její nadřazenou (mateřskou) složku (každá složka je podsložkou složky jiné)<br />
        - úroveň komplexity (Je užívána pro kvalitu hesla. Při vytvoření nové položky nesmí být její heslo jednodušší než zde stanovená úroveň)<br />
        - obnovovací lhůtu v měsících (je třeba, pokud chcete zavést nucené obnovování hesla po uplynutí určitého období).    
    </div>
    <h3><a href='#'>Změnit stávající SLOŽKU</a></h3>
    <div>
        Chcete-li změnit u složky název, komplexitu hesla, nadřazenou složku či obnovovací lhůtu, klikněte na políčko.<br />
        Poté lze políčko upravovat. Změňte odpovídající hodnotu a pro uložení klikněte na symbol <img src='includes/images/disk_black.png' alt='' /> , nebo zrušte kliknutím na symbol <img src='includes/images/cross.png' alt='' /> .<br />
        <p style='text-align:center;'>
        <img src='includes/images/help/folders_1.png' alt='' />
        </p>
        <div style='margin:10px Opx 0px 20px;'>
            Nezapomeňte, že změníte-li nadřazenou složku, budou všechny podsložky této složky rovněž přemístěny.
        </div>
    </div>
    <h3><a href='#'>Odstranit SLOŽKU</a></h3>
    <div>
        Chcete-li odstranit SLOŽKU, klikněte na symbol <img src='includes/images/folder--minus.png' alt='' />.<br /> 
        Tím kompletně odstraníte celý obsah složky včetně podsložek. Buďte tedy opatrný/á !!!!
        <p style='text-align:center;'>
        <img src='includes/images/help/folders_2.png' alt='' />
        </p>
    </div>
    <h3><a href='#'>Speciální nastavení</a></h3>
    <div>
        Složky mají dvě speciální nastavení.<br />
        1.nastavení umožňuje tvorbu položek bez ohledu na stanovenou komplexitu hesla.<br /> 
        2.nastavení umožňuje změnu položek bez ohledu na stanovenou komplexitu hesla.<br /> 
        Obě nastavení lze také kombinovat a aktivovat je pouze dočasně.   
        <p style='text-align:center;'>
        <img src='includes/images/help/folders_3.png' alt='' />
        </p>
    </div>
</div>
";
?>
