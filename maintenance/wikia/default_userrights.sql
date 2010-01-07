delete from user_groups where ug_group="staff";
insert into user_groups(ug_user, ug_group) values
(/*Adamarket*/1499323,'staff'),
(/*Adi3ek*/259228,'staff'),
(/*Angela*/2,'staff'),
(/*Angies*/67261,'staff'),
(/*Avatar*/349903, 'staff'),
(/*BartL*/80238,'staff'),
(/*BillK*/38903,'staff'),
(/*BladeBronson*/140142,'staff'),
(/*CatherineMunro*/108559,'staff'),
(/*Crucially*/182546,'staff'),
(/*Dmurphy*/138300,'staff'),
(/*Eloy.wikia*/51098,'staff'),
(/*Emil*/27301,'staff'),
(/*Gil*/20251,'staff'),
(/*Inez*/51654,'staff'),
(/*Jeremie*/123457,'staff'),
(/*Jimbo Wales*/13,'staff'),
(/*JoePlay*/171752,'staff'),
(/*Kirkburn*/126761,'staff'),
(/*KimberlySue*/1670804,'staff'),
(/*KyleH*/265264,'staff'),
(/*Lleowen*/261184,'staff'),
(/*Macbre*/119245,'staff'),
(/*Marooned*/250810,'staff'),
(/*Meitar*/967856,'staff'),
(/*Moli.wikia*/115748,'staff'),
(/*Ppiotr*/60069,'staff'),
(/*Przemek wikia*/157013,'staff'),
(/*Ri3mann*/247550,'staff'),
(/*Sannse*/8,'staff'),
(/*Sarah Manley*/1613840,'staff'),
(/*Scarecroe*/10637, 'staff'),
(/*Sean Colombo*/1491391, 'staff'),
(/*Shahid*/152910,'staff'),
(/*TomekO*/1787946,'staff'),
(/*TOR*/23865,'staff'),
(/*Toughpigs*/10370,'staff'),
(/*Uberfuzzy*/161697, 'staff'),
(/*VickyBC*/1066766, 'staff'),
(/*WikiaBot*/269919,'staff'),
(/*Xean*/1627201,'staff'),
(/*Zuirdj*/47,'staff'),
(/*WikiaStaff*/1342530,'staff')
;

delete from user_groups where ug_group="helper";
insert into user_groups(ug_user, ug_group) values
(/*Bola*/126681,'helper'),
(/*Cizagna*/35784,'helper'),
(/*DaNASCAT*/22224,'helper'),
(/*Defchris*/1636,'helper'),
(/*Game widow*/390665,'helper'),
(/*JParanoid*/10970,'helper'),
(/*Kacieh*/1704661,'helper'),
(/*Merrystar*/11001,'helper'),
(/*MtaÄ*/826221, 'helper'),
(/*Multimoog*/20290,'helper'),
(/*Peteparker*/122657, 'helper'),
(/*Richard1990*/25261,'helper'),
(/*Schikado*/1214031,'helper'),
(/*Tedjuh10*/1171508,'helper'),
(/*Tommy6*/239851,'helper'),
(/*Wagnike2*/99965,'helper'),
(/*Zeyi*/874612,'helper')
;

delete from user_groups where ug_user in (
/*Default*/49312,
/*Maintenance script*/375130,
/*WikiaBot*/269919
) and ug_group="bot";
insert into user_groups(ug_user, ug_group) values
(/*Default*/49312,'bot'),
(/*Maintenance script*/375130,'bot'),
(/*WikiaBot*/269919,'bot')
;

delete from user_groups where ug_group="vstf";
insert into user_groups(ug_user, ug_group) values
(/*Charitwo*/184532,'vstf'),
(/*Eulalia459678*/223485,'vstf'),
(/*Joeyaa*/417287,'vstf')
;
