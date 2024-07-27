        //globalium
	//de 2
	globalium=[];

//	pos:situa(PT,FN,OS)...

	globalium['ANA']={pos:situa(30,-30,0),color:[0,40,255],title:'Analisi',type:'activitat',descr:'Separacio de detalls, aspectes, qualitats'}; // Analisi (fenomenica teorica)
	globalium['EXP']={pos:situa(-30,-30,0),color:[0,255,0],title:'Experiencia',type:'activitat',descr:'Captar dades a traves dels sentits. ambit d\'observacio dels sentits i d\'actuacio dels organs efectors'};
        globalium['AMO']={pos:situa(-30,30,0),color:[255,0,0],title:'Amor',type:'activitat',descr:''}; //Amor (sintesi noumenica practica)
        globalium['SIN']={pos:situa(30,30,0),color:[255,0,255],title:'Sintesi',type:'activitat',descr:'Saviesa profunda, integradora'};

        globalium['COS']={pos:situa(-80,0,0),color:[255,0,0],title:'Cosmos',type:'estat',descr:'Conjunt de totes les coses existents. Univers concret desplegat.'}; // Cosmos
        globalium['COV']={pos:situa(80,0,0),color:[0,40,255],title:'Cosmovisi&oacute;',type:'estat',descr:''}; //Cosmovisio
        globalium['EXC']={pos:situa(0,-80,0),color:[0,255,0],title:'Exactitud',type:'estat',descr:'Dades, constatacions'}; // Exactitud - actes aqui i ara
        globalium['CMN']={pos:situa(0,80,0),color:[255,0,255],title:'Comuni&oacute;',type:'estat',descr:''};  // Comunio

        globalium['INT']={pos:situa(0,0,80),color:[0,0,0],title:'Intencio',type:'estat',descr:''};

	//Afinitat categories aprop
        globalium['AFI']={pos:situa(0,0,-80),color:[0,0,0],title:'Afinitat',type:'estat',descr:'Objecte mundà. Atracció mútua. Valencia. L\'objecte esta definit, té un límit, un contorn, i per tant una afinitat, un camp magnètic que el permet relacionar-se amb els demés. L\'afinitat cohesiona el món.'};
        globalium['OBL']={pos:situa(-10,0,-80),color:[255,0,0],title:'Obligacio',type:'estat',descr:'Obligació. Conjunt de funcions pràctiques limitades a la realitat estructurada de l\'objecte mundà. Manca de lliure albir.'};
	globalium['ECN']={pos:situa(-10,-10,-80),color:[255,0,0],title:'Economia',type:'estudi',descr:'Funcionament dels objectes en el món pràctic i fenomenlògic. Ordre i repartiment del bé comú.'};   // Economia// ordre bo i util
        globalium['FUN']={pos:situa(0,-10,-80),color:[0,255,0],title:'Funci&oacute;',type:'estat',descr:'Magnitud fisica derivada en un punt.'};// OBJ MON FEN Funcio
	globalium['DET']={pos:situa(10,-10,-80),color:[0,255,0],title:'Determinacio',descr:'estat'};    // OBJ MON TEO FEN // Determinacio
	globalium['CNV']={pos:situa(10,0,-80),color:[0,40,255],title:'Convencio',type:'estat',descr:'Convenció. Definició dels objectes acceptada.'};
	globalium['ARQ']={pos:situa(10,10,-80),color:[255,0,255],title:'Arquetip',descr:'Arquetip, ideica mundana, models, esquemes, sistemes de categories.'}; // Arquetip
        globalium['OGN']={pos:situa(0,10,-80),color:[255,0,255],title:'Organ',type:'estat',descr:'Orgue. Organisme en el sentit noumenic, no practic. ERROR. Organisme compleix funcio practica i fenomenica. Per tant això ser la IDenTitat trascendental de l\'objecte.'}; // OBJ MON NOU  Organ
        globalium['ECL']={pos:situa(-10,10,-80),color:[255,0,255],title:'Ecologia',type:'estudi',descr:'Funció pràctica i espiritual de l\'objecte mundà. Sistema concret pràctic i mundà. Objecte trascendent mundà i pràctic. Sostenibilitat dels equilibris de la naturalesa física i social.'}; // Ecologia,sostenibilitat


	

        globalium['FEL']={pos:situa(0,0,25),color:[0,0,0],title:'Felicitat',type:'estat',descr:''};
        globalium['BOS']={pos:situa(0,0,-25),color:[0,0,0],title:'Bosso',type:'estat',descr:''};

        globalium['STT']={pos:situa(30,0,30),color:[0,40,255],title:'Sentit',type:'resultat',descr:'Discerniment mental vivencial. Intel.ligencia. Endevinar. No confondre amb sentits sensorials.'};
        globalium['STM']={pos:situa(-30,0,30),color:[255,0,0],title:'Sentiment',type:'resultat',descr:'concerniment vital'};
        globalium['SGT']={pos:situa(30,0,-30),color:[0,40,255],title:'Significat',type:'resultat',descr:''};
        globalium['SGE']={pos:situa(-30,0,-30),color:[255,0,0],title:'Signe',type:'resultat',descr:'conscerniment extens = senyal, imatge associada a significat.<br><br><b>Núvol d\'afins:</b> Graf, Fotografia, Litografia, Geografia (geometria), Hagiografia, Historiografia, Poligrafia, Estenografia, Holografia, Cal·ligrafia, Telegrafia (telemetria), Epigrafia, Pluviògraf (pluviòmetre), Xilografia, Gràfic, Gràfica, Grafit, Grafitti, Esgrafiat, Grafisme, Grafologia, Paràgraf, Autògraf, Pantògraf, Biografia, Bibliografia'};


        globalium['CIE']={pos:situa(0,-30,-30),color:[0,255,0],title:'Ciencia',type:'ambit',descr:'Activitat intelectual i pràctica que abarca l\'estudi sistemàtic de l\'estructura i el comportament del món físic i natural, a través de l\'observació i l\'experimentació.'};
        globalium['ART']={pos:situa(0,-30,30),color:[0,255,0],title:'Art',type:'ambit',descr:'Expressió o aplicació de l\'habilitat creativa humana i la imaginació.'};
        globalium['MTF']={pos:situa(0,30,-30),color:[255,0,255],title:'Metafisica',type:'ambit',descr:'Transcend&egrave;ncia objectiva. Estudi de l\'&eacute;ssser o de l\'existencia, o concepcions profundes de la realitat (NO&Uuml;). &Eacute;s allo que creiem que son els &eacute;ssers. Tamb&eacute; el que es considera l\'essencia de cada cosa. El plus que el tot afegeix a la suma de les parts. T&eacute; com a objectiu descriure o posar les categories i relacions per a definir els ens.<br><br>Ex: Quan diem "aixo es una persona". Cientificament es un orgamisme, pero de la idea de donar-li el sentit de persona, amb tot el que comporta, perque objectivament aixi es, passem a dir que allo es una persona, afegint-li aquest plus. No s\'ha de confondre ideologia amb metafisica, les idees son nomes teoriques, mentre que la metafisica es teorica i practica.',related:'Ontologia: Fa la mateixa funcio que la metafisica pero als objectes sensibles, fisics. Ex. "aixo es una cadira". Una concepcio mes amplia de la metafisica podria incloure la ontologia.<br><br>Holisme: Quan es relatiu a sistemes (fisics, biologics, quimics, socials, economics, mentals, linguistics) i les seves propietats, vist com un tot. (Ex. cosmos, pensament sistemic, ubuntu). El tot que supera les parts.<br><br>Natura:'};
        globalium['MTP']={pos:situa(0,30,30),color:[255,0,255],title:'Metaps&iacute;quica',type:'ambit',descr:'espiritualitat',related:'FE/CREEN&Ccedil;A:Quan &eacute;s relatiu a concepcions religioses de deu o de principis universals de caire espiritual'};

        globalium['CAS']={pos:situa(-10,0,0),color:[255,0,0],title:'Caos',type:'resultat',descr:'Desordre'}; //caos      
        globalium['CAV']={pos:situa(10,0,0),color:[0,40,255],title:'Caovisi&oacute;',type:'resultat',descr:''};
        globalium['CFN']={pos:situa(0,10,0),color:[255,0,255],title:'Confinament',type:'resultat',descr:'Misteri ocult de la realitat transcendent. Destí i clausura. Tot es possible a partir d\'una substància plasmàtica primigènia determinada, limitada. '}; //confinament
        globalium['ATZ']={pos:situa(0,-10,0),color:[0,255,0],title:'Atzar',type:'resultat',descr:'Caixa de trons, generador de reaccions aleatòries.'}; // es mes aprop del plasma/caos que de l'ordre/mon. Es fenomenic perque es pot percebre. Pero potser tambe hi ha un atzar noumenic, de fisica quanticam, no?
     
	// ------- de 3 -----

        globalium['MIS']={pos:situa(-30,30,30),color:[255,0,255],title:'M&iacute;stica',type:'estudi',descr:''};
        globalium['MIT']={pos:situa(30,30,30),color:[0,40,255],title:'M&iacite;tica',type:'estudi',descr:''};
        globalium['EST']={pos:situa(30,-30,30),color:[0,255,0],title:'Est&egrave;tica',type:'estudi',descr:'Basat en la forma i l\'expressio, porta implicita la contraduccio, no dedu&iuml;ble'};
        globalium['PSI']={pos:situa(-30,-30,30),color:[255,0,0],title:'Pi&iacute;quica',type:'estudi',descr:'Realitat manifesta vivencial. Introspecció, interior o intimitat.  Alè existencial. Experiència subjectiva. Pràctica artística. Fenomen emocional. Món íntim, Desició sentimental. Desig atzarós. Relatiu a l\'anima, a la psique. Interior, autocreat, vivencial. Part vivencial i emocional, diferent de l\'intel.lectual'};
        globalium['ETI']={pos:situa(-30,30,-30),color:[255,0,0],title:'&Egrave;tica',type:'estudi',descr:''};
        globalium['IDE']={pos:situa(30,30,-30),color:[255,0,255],title:'Ideica',type:'estudi',descr:''};
        globalium['LOG']={pos:situa(30,-30,-30),color:[0,40,255],title:'L&ograve;gica',type:'estudi',descr:'Dedu&iuml;ble de principis'};
        globalium['TEC']={pos:situa(-30,-30,-30),color:[0,255,0],title:'T&egrave;cnica',type:'estudi',descr:'ben resolt a traves de protocol ben definit',related:'fisica'};

	//plasmatiques
        globalium['PRB']={pos:situa(10,-10,0),color:[0,255,0],title:'Probabilitat',type:'estat',descr:'Poc delimitat, poc definit, imprevisible'};
        globalium['MGM']={pos:situa(-10,10,0),color:[255,0,255],title:'Magma',type:'estat',descr:'Força viva de l\'univers amagada, encapsulada, comprimida, contemplativa en la seva llavor i principi de vida germinal. Forma caòtica de l\'amor. Pasta conreta fonamental (subquàntica) conscient.'};
//        globalium['TRB']={pos:[posx(25,cat['dim']['PRA']),posy(25,cat['dim']['FEN']),0],color:[0,0,0],title:'Turbulencia',type:'estat',descr:'Que escapa al control, de regularitat canviants'};
	globalium['TRB']={pos:situa(-10,-10,0),color:[255,0,0],title:'Turbulencia',type:'estat',descr:'Que escapa al control, de regularitat canviants'};
        globalium['SLM']={pos:situa(10,10,0),color:[0,40,255],title:'Sublimitat',type:'estat',descr:''};

	//mundanes
        globalium['HAR']={pos:situa(50,50,0),color:[255,0,255],title:'Harmonia',type:'estat',descr:'Harmonia'};
        globalium['PCS']={pos:situa(50,-50,0),color:[0,40,255],title:'Precisio',type:'estat',descr:'Delimitat, finit, previsible'};
        globalium['RGN']={pos:situa(-50,50,0),color:[255,0,0],title:'Regne',type:'estat',descr:'Regne'};
        globalium['POL']={pos:situa(-50,-50,0),color:[0,255,0],title:'Polidesa',type:'estat',descr:'acabat, ben presentat, bona educacio'};

        //Categories plasmatiques prop de felicitat
        globalium['TRS']={pos:situa(0,-10,25),color:[0,255,0],title:'Transit',type:'estat',descr:'Estat de trànsit, inspiració perceptiva, medium, estat interior excitat del geni fecund. Sessió mística, suspensió de sentits usuals.'};
        globalium['PAS']={pos:situa(-10,-10,25),color:[255,0,0],title:'Passio',type:'estat',descr:''};
        globalium['EBR']={pos:situa(-10,0,25),color:[255,0,0],title:'Ebrietat',type:'estat',descr:'Embriaguesa, ebrietat. Ànim pertornat pel transport d\'una passió, per plaer o dolor'};
        globalium['AKA']={pos:situa(-10,10,25),color:[255,0,255],title:'Akaixa',type:'estat',descr:'eter,cel,quintaessencia,esperit concepte indi espiritual'};
        globalium['LET']={pos:situa(0,10,25),color:[255,0,255],title:'Let&agrave;rgia',type:'estat',descr:'Son. Evasió. Absència. Silenci. Dejuni. Pobresa. Vigília. Abstinència. Confinament subjectiu, transcèncencia vivencial plasmàtica. Repòs ruminant de l\'espert. Suspensió de l\'ús dels sentits, de l\'activitat i de les facultats de l\'ànim.'};
        globalium['TIA']={pos:situa(10,10,25),color:[0,40,255],title:'Tiamat',type:'estat',descr:''};
        globalium['FOL']={pos:situa(10,0,25),color:[0,40,255],title:'Folia',type:'estat',descr:''};
        globalium['GLO']={pos:situa(10,-10,25),color:[0,255,0],title:'Gloria',type:'estat',descr:''};

	//Categories plasmatiques prop d'intenció
	globalium['GEN']={pos:situa(0,10,80),color:[255,0,255],title:'Geni',type:'estat',descr:'Entitat de l\'èsser.'};
        globalium['ECU']={pos:situa(-10,10,80),color:[255,0,255],title:'Ecumene',descr:'resultat'}; // SUB MON PRA NOU // Ecumene - mon civilitzat
	globalium['DSG']={pos:situa(-10,0,80),color:[255,0,0],title:'Desig',type:'estat',descr:''};
        globalium['COM']={pos:situa(-10,-10,80),color:[255,0,0],title:'Comunitat',descr:'resultat'}; // SUB MON PRA FEN // Comunitat
	globalium['AGU']={pos:situa(0,-10,80),color:[0,255,0],title:'Agudesa',type:'estat',descr:'Agudesa dels sentits sensorials, perspicàcia, clarividència, alerta, penetració intel·lectual'}; // SUB MON FEN
	globalium['BEL']={pos:situa(10,-10,80),color:[0,255,0],title:'Bellesa',descr:'estat'}; // SUB MON TEO FEN // Bellesa
	globalium['AST']={pos:situa(10,0,80),color:[0,40,255],title:'Astucia',type:'estat',descr:''};
        globalium['DIV']={pos:situa(10,10,80),color:[0,40,255],title:'Divinitat',descr:'Guiar'}; // SUB MON TEO NOU // Divinitat




        // ----- de 4 -------
	//Categories plasmatiques prop de BOSó
        globalium['APE']={pos:situa(-10,10,-25),color:[255,0,255],title:'Apeiron',type:'estat',descr:''};
	globalium['ORG']={pos:situa(0,10,-25),color:[255,0,255],title:'Org&oacute;',type:'estat',descr:''};
	globalium['ARK']={pos:situa(10,10,-25),color:[0,40,255],title:'Arkhe',type:'estat',descr:''};
        globalium['RAR']={pos:situa(10,0,-25),color:[0,40,255],title:'Raresa',type:'estat',descr:''};
	globalium['IDT']={pos:situa(10,-10,-25),color:[0,255,0],title:'Indeterminacio',type:'estat',descr:''};
        globalium['ONA']={pos:situa(0,-10,-25),color:[0,255,0],title:'Ona',type:'estat',descr:'Manifestació plasmàtica estructurada producte d\'un moviment vibratori.'};
	globalium['ACC']={pos:situa(-10,-10,-25),color:[255,0,0],title:'Accio',type:'estat',descr:'Realitat manifesta estucturada plasmàtica. Pràctica fenomènica, objectiva i plasmàtica.'}; 
        globalium['PRD']={pos:situa(-10,0,-25),color:[255,0,0],title:'Prodigi',type:'estat',descr:''};



        loadCategories("glo",globalium,{textcolor:'255,255,255',borderThickness: 0.1,fontsize: 70, borderalpha:0.5, bgalpha:0.3});  

/*	var custom={];
	loadCategories(custom,data,{textcolor:'255,255,255',borderThickness: 2,fontsize: 12, borderalpha:0.5, bgalpha:0.3});	
*/

