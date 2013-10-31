-- --------------------------------------------------------
-- Host:                         securella.ru
-- Server version:               5.5.31-1~dotdeb.0 - (Debian)
-- Server OS:                    debian-linux-gnu
-- HeidiSQL version:             7.0.0.4194
-- Date/time:                    2013-10-31 17:09:54
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table reviews.DictClass
CREATE TABLE IF NOT EXISTS `DictClass` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ParentId` int(11) unsigned DEFAULT NULL,
  `ClassName` varchar(100) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UX_Name_DictClass` (`ClassName`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Регистр всех зарегистрированных в системе классво объектов';

-- Dumping data for table reviews.DictClass: 4 rows
/*!40000 ALTER TABLE `DictClass` DISABLE KEYS */;
INSERT IGNORE INTO `DictClass` (`Id`, `ParentId`, `ClassName`, `Description`) VALUES
	(1, 2, 'TreeApplication', 'Веб сайт'),
	(2, 3, 'TreeObjectPage', 'Страница'),
	(3, NULL, 'TreeObjectNode', 'Узел'),
	(4, 2, 'TreeCatalogRubric', 'Каталог: рубрика');
/*!40000 ALTER TABLE `DictClass` ENABLE KEYS */;


-- Dumping structure for table reviews.Object
CREATE TABLE IF NOT EXISTS `Object` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `ParentId` int(11) unsigned DEFAULT NULL,
  `OrderBy` int(5) unsigned NOT NULL DEFAULT '0',
  `Caption` varchar(250) COLLATE utf8_bin NOT NULL,
  `Class` int(11) unsigned NOT NULL,
  `IsActive` int(1) unsigned NOT NULL DEFAULT '1',
  `DateCreate` int(11) unsigned NOT NULL,
  `DateUpdate` int(11) unsigned NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UXParentIdOrderBy_Object` (`ParentId`,`OrderBy`),
  KEY `IX_IsActive_Object` (`IsActive`),
  KEY `IXName_Object` (`Name`),
  KEY `IXParentIdClass_Object` (`ParentId`,`Class`)
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Страницы сайта с древовидной иерархией';

-- Dumping data for table reviews.Object: 8 rows
/*!40000 ALTER TABLE `Object` DISABLE KEYS */;
INSERT IGNORE INTO `Object` (`Id`, `Name`, `ParentId`, `OrderBy`, `Caption`, `Class`, `IsActive`, `DateCreate`, `DateUpdate`) VALUES
	(1, 'top_page_rus', NULL, 1, 'Все отзывы рунета', 1, 1, 1368690217, 1377713608),
	(3, '404', 1, 2, '404 - Страница не найдена', 2, 1, 1368690217, 1379433762),
	(63, NULL, 1, 9, 'Контакты', 2, 1, 1370347284, 1377713608),
	(64, NULL, 1, 10, 'О проекте', 2, 1, 1370347344, 1377713608),
	(65, NULL, 1, 11, 'Карта сайта', 2, 1, 1370347534, 1377713608),
	(105, NULL, 103, 2, 'Умывальники', 4, 1, 1380727215, 1381513025),
	(104, NULL, 103, 1, 'Телевизоры', 4, 1, 1380650744, 1381744010),
	(103, NULL, 1, 1, 'Отзывы', 4, 1, 1380650651, 1381508017);
/*!40000 ALTER TABLE `Object` ENABLE KEYS */;


-- Dumping structure for table reviews.ObjectApplication
CREATE TABLE IF NOT EXISTS `ObjectApplication` (
  `ObjectId` int(11) unsigned NOT NULL,
  `Address` varchar(300) COLLATE utf8_bin NOT NULL,
  `Language` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`ObjectId`),
  UNIQUE KEY `IX__Address__ObjectApplication` (`Address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table reviews.ObjectApplication: 1 rows
/*!40000 ALTER TABLE `ObjectApplication` DISABLE KEYS */;
INSERT IGNORE INTO `ObjectApplication` (`ObjectId`, `Address`, `Language`) VALUES
	(1, 'securella.ru', 1);
/*!40000 ALTER TABLE `ObjectApplication` ENABLE KEYS */;


-- Dumping structure for table reviews.ObjectCatalogRubric
CREATE TABLE IF NOT EXISTS `ObjectCatalogRubric` (
  `ObjectId` int(11) unsigned NOT NULL,
  `Rating` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`ObjectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table reviews.ObjectCatalogRubric: ~3 rows (approximately)
/*!40000 ALTER TABLE `ObjectCatalogRubric` DISABLE KEYS */;
INSERT IGNORE INTO `ObjectCatalogRubric` (`ObjectId`, `Rating`) VALUES
	(103, NULL),
	(104, NULL),
	(105, NULL);
/*!40000 ALTER TABLE `ObjectCatalogRubric` ENABLE KEYS */;


-- Dumping structure for table reviews.ObjectPage
CREATE TABLE IF NOT EXISTS `ObjectPage` (
  `ObjectId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Header` varchar(500) COLLATE utf8_bin NOT NULL,
  `Title` varchar(500) COLLATE utf8_bin NOT NULL,
  `Path` varchar(100) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin,
  `Content` text COLLATE utf8_bin,
  `MetaKeywords` text COLLATE utf8_bin,
  `MetaDescription` text COLLATE utf8_bin,
  PRIMARY KEY (`ObjectId`),
  KEY `IX_Path_ExtPage` (`Path`)
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Расширенная таблица с полями для объектов типа Страница';

-- Dumping data for table reviews.ObjectPage: 8 rows
/*!40000 ALTER TABLE `ObjectPage` DISABLE KEYS */;
INSERT IGNORE INTO `ObjectPage` (`ObjectId`, `Header`, `Title`, `Path`, `Description`, `Content`, `MetaKeywords`, `MetaDescription`) VALUES
	(63, 'Контакты', 'Контакты', 'contacts', '', '<p>\r\n	<span style="font-size: medium;">Все способы контакта с нами:</span></p>\r\n<p style="margin-left: 40px;">\r\n	<span style="font-size: medium;">Способ для тех, кто много пишет <a href="javascript:location.href=\'mailto:\'+String.fromCharCode(105,110,102,111,64,116,111,112,45,112,97,103,101,46,114,117)+\'?\'">info@top-page.ru</a></span></p>\r\n<p style="margin-left: 40px;">\r\n	<span style="font-size: medium;">Способ для тех, кто не любит много читать&nbsp; <a href="http://twitter.com/toppage_pr">https://twitter.com/#!/Top_Page</a></span></p>\r\n<p style="margin-left: 40px;">\r\n	<span style="font-size: medium;">Способ для тех, кто любит комментировать <a href="http://top-page.livejournal.com">www.top-page.livejournal.com</a></span></p>\r\n<p style="margin-left: 40px;">\r\n	&nbsp;</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	<meta content="text/html; charset=utf-8" http-equiv="content-type" />\r\n	<span style="font-size: medium; "><a href="http://www.co-working.su/project-management/">Управление проектом</a>&nbsp;- Компания &quot;Коворкинг Центр&quot;,&nbsp;</span><span style="font-size: medium; ">www.co-working.su</span></p>\r\n', '', ''),
	(3, '404 - Страница не найдена', 'Запрашиваемая страница не найдена - Стартовая страница', '404', '<p>\r\n	NOTHING!</p>\r\n', '<h1>\r\n	Запрашиваемая страница не найдена (404)</h1>\r\n<div class="error">\r\n	<p>\r\n		<span style="font-size: medium;">Страница, к которой Вы обратились не существует.</span></p>\r\n	<p>\r\n		<span style="font-size: medium;">Возможно был неправильно набран адрес страницы,</span></p>\r\n	<p>\r\n		<strong><span style="font-size: medium;">или</span></strong></p>\r\n	<p>\r\n		<span style="font-size: medium;">Ссылка, по которой Вы перешли на сайт уже не существует!</span></p>\r\n	<p>\r\n		&nbsp;</p>\r\n	<p>\r\n		<span style="font-size: medium;">Попробуйте начать свой путь со <a href="/">стартовой страницы</a>.</span></p>\r\n	<p>\r\n		&nbsp;</p>\r\n	<p>\r\n		<strong><span style="font-size: medium;">Спасибо за то, что пользуетесь Top-Page.ru</span></strong></p>\r\n</div>\r\n', 'top-page.ru', 'top-page.ru'),
	(103, 'Отзывы', 'Отзывы', 'catalog', '', '', '', ''),
	(104, 'Телевизоры', 'Телевизоры', '1', '', '', '', ''),
	(105, 'Умывальники', 'Умывальники', '2', '', '', '', ''),
	(1, 'Все отзывы рунета', 'Все отзывы рунета - securella.ru', '', '', '', '', ''),
	(64, 'Правила пользования сервисом Top-Page.ru', 'Правила пользования сервисом Визуальных закладок Top-Page.ru', 'about', '', '<h1>\r\n	<span style="font-size: medium;">1. Описание Услуг</span></h1>\r\n<p>\r\n	Сервис Визуальных закладок Top Page.ru (далее - Сервис) - это стартовая страница&nbsp;с&nbsp;<a href="http://top-page.ru">Визуальными закладками</a>&nbsp;для быстрого и простого доступа&nbsp;к любимым сайтам, добавленные &nbsp;Пользователя. Администратор и Правообладатель Сервиса ООО &quot;Топ Пэйдж&quot; (далее - Администратор) предоставляет Сервис бесплатно.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	<strong style="font-size: medium;">2</strong><strong style="font-size: medium;">. Общие положения</strong></p>\r\n<p>\r\n	2.1. Настоящие Правила использования Сервиса (далее &mdash; Правила) устанавливают правила и условия использования Сервиса Пользователями, которые после регистрации или без таковой, осуществляя использование Сервиса и его служб, становятся Пользователями Сервиса и услуг, предоставляемых Администратором.</p>\r\n<p>\r\n	2.2. Использование Пользователем Сервиса, любых его служб, функционала - означает безоговорочное согласие Пользователя со всеми пунктами настоящих Правил и безоговорочное принятие их условий с обязательствами соблюдать обязанности возложенные на Пользователя по настоящим Правил. Факт использования Пользователем Сервиса, любых его служб, функционала, а также регистрация Пользователя на Сервисе (создание учетной записи) является полным и безоговорочным акцептом настоящих Правил, незнание которого не освобождает Пользователя от ответственности за несоблюдение его условий.</p>\r\n<p>\r\n	2.3. Согласие Пользователя использовать Сервис после любых изменений Правил означает его согласие с такими изменениями и/или дополнениями.</p>\r\n<p>\r\n	2.4. Пользователь обязуется регулярно, не реже 1 (одного) раза в 14 (четырнадцать) дней знакомиться с содержанием настоящих Правил, в целях своевременного ознакомления с его изменениями.</p>\r\n<p>\r\n	2.5. Администратор оставляет за собой право по своему личному усмотрению изменять и (или) дополнять Правила в любое время без предварительного и (или) последующего уведомления Пользователя. Действующая редакция Правил доступна в интерфейсе Сервиса по адресу в сети Интернет: http://top-page.ru/about/.</p>\r\n<p>\r\n	2.6. Если Вы (Пользователь) не согласны соблюдать настоящие Правила, не используйте Сервис, а если являетесь зарегистрированным Пользователем, удалите свою учетную запись и/или прекратите использование Сервиса.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	<strong><span style="font-size: medium;">3. Права и обязанности Администратора</span></strong></p>\r\n<p>\r\n	<span style="font-size: medium;">3.1 Обязанности Администратора заключается исключительно в обеспечении предоставления технической возможности получения Пользователем доступа к Сервису, в порядке, определённом настоящими Правилами.</span></p>\r\n<p>\r\n	3.2. Администратор оставляет за собой право по своему собственному усмотрению изменять или удалять любые элементы и составные части Сервиса, приостанавливать, ограничивать или прекращать доступ Пользователя ко всем или к любому из разделов Сервиса в любое время по любой причине или без объяснения причин, с предварительным уведомлением или без такового (по усмотрению Администратора). При этом Стороны соглашаются, что Администратор не отвечает за любой вред, который может быть причинен Пользователю такими действиями;</p>\r\n<p>\r\n	3.3. Администратор вправе устанавливать любые ограничения в использовании Сервиса, в любое время изменять настоящее Соглашение в одностороннем порядке, без получения согласия Пользователя;</p>\r\n<p>\r\n	3.4. Администратор вправе осуществлять рассылки Пользователям сообщений, содержащих организационно-техническую или иную информацию о возможностях Сервиса;</p>\r\n<p>\r\n	3.5. Администратор вправе размещать рекламную и/или иную информацию в любом разделе Сервиса, а так же в закладках Пользователя, на что Пользователь дает ему согласие в настоящих Правилах;</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	<strong><span style="font-size: medium;">4. Права и обязанности Пользователя</span></strong></p>\r\n<p>\r\n	4.1. Пользователь обязуется использовать Сервис только в законных целях, соблюдать действующее законодательство РФ, иное применимое законодательство, а также права и законные интересы Администратора;</p>\r\n<p>\r\n	4.2. Пользователь обязан воздерживаться от осуществления действий, направленных на дестабилизацию работы Сервиса, осуществления попыток несанкционированного доступа к Сервису, результатам интеллектуальной деятельности, размещенным на нем, а также от осуществления любых иных действий, нарушающих права Администратора и/или третьих лиц;</p>\r\n<p>\r\n	4.3. Пользователь не имеет права воспроизводить, повторять, копировать, продавать, перепродавать, а также использовать любым способом для каких-либо коммерческих целей Сервис и (или) какие-либо части содержимого Сервиса без согласия Администратора;</p>\r\n<p>\r\n	4.4. Пользователь имеет право прекратить использование Сервиса и отказаться от созданной им учетной записи, направив Администратору на адрес электронной почты info@top-page.ru со своего адреса электронной почты, указанного при регистрации, запрос на удаление учетной записи с Сервиса. Администратор удаляет учетную запись Пользователя в течение 30 (тридцати) дней после получения его запроса соответствующего условиям указанным выше;</p>\r\n<p>\r\n	4.5. Пользователь обязан представить при регистрации (создании учетной записи) точную, актуальную и полную информацию о себе, которая может быть запрошена регистрационными формами Сервиса (далее - Персональная информация).</p>\r\n<p>\r\n	4.6. Пользователь обязан принимать надлежащие меры для обеспечения сохранности учетной записи Пользователя (включая адреса электронной почты) и пароля и несет ответственность за все действия, совершенные на Сервисе под его учетной записью (логином и паролем). В связи с указанным Пользователь обязан осуществлять выход из своей учетной записи (завершать каждую сессию по кнопке &laquo;Выход&raquo;) перед переходом на сторонние сайты или закрытием браузера (Интернет-обозревателя). Пользователь обязан незамедлительно уведомить Администратора о любых случаях доступа на Сервис третьими лицами под учетной записью Пользователя.</p>\r\n<p>\r\n	4.7. Пользователь несет полную ответственность за любые действия, совершенные им с использованием его учетной записи, а также за любые последствия, которые могло повлечь или повлекло подобное его использование.</p>\r\n<p>\r\n	4.8. Пользователь, осуществляя использование Сервиса, даёт Администратору своё согласие на получение рекламной информации, размещаемой на Сервисе. В случае несогласия Пользователя с настоящим положением Правил, Пользователь вправе не использовать Сервис, отказаться от его использования полностью.</p>\r\n<p>\r\n	4.9. Пользователь обязан выполнять иные обязанности, установленные настоящими Правилами.<strong><span style="font-size: medium;">&nbsp;</span></strong></p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	<strong><span style="font-size: medium;">5. Стороннее ПО</span></strong></p>\r\n<p>\r\n	<span style="font-size: medium;">5.1. Все стороннее ПО, расширяющее и/или дополняющее функционал Сервиса - расширения для браузеров, приложения для мобильных платформ и прочее, предаставляется Администрацией &nbsp;Сервиса &quot;как есть&quot;.</span></p>\r\n<p>\r\n	<span style="font-size: medium;">5.2. Администрация Сервиса несёт ответственность за полную интеграцию ПО с Сервисом, обеспечивая стабильную работу ПО, снимая с себя ответственность за действия Пользователя при установке, настройке и использовании ПО.</span></p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	<strong><span style="font-size: medium;">6. Контактная информация</span></strong></p>\r\n<p>\r\n	<span style="font-size: medium;">Вопросы по поводу работы Сервиса можно задать по адресу&nbsp;<a href="mailto:info@top-page.ru?subject=%D0%92%D0%BE%D0%BF%D1%80%D0%BE%20%D0%BE%20%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%B5%20%D1%81%D0%B5%D1%80%D0%B2%D0%B8%D1%81%D0%B0%20Top-Page.ru">info@top-page.ru</a></span></p>\r\n', 'Стартовая страница - визуальные закладки сайтов, быстрый поиск.', 'Стартовая страница - визуальные закладки сайтов, быстрый поиск, игры.'),
	(65, 'Карта сайта', 'Карта сайта', 'map', '', '<div id="map-list">\r\n	<ul class="lv-0">\r\n		<li>\r\n			<a href="/about/" title="О проекте">О проекте</a></li>\r\n		<li>\r\n			<a href="/contacts/" title="Контакты">Контакты</a></li>\r\n	</ul>\r\n</div>\r\n', '', '');
/*!40000 ALTER TABLE `ObjectPage` ENABLE KEYS */;


-- Dumping structure for table reviews.ObjectPageUrl
CREATE TABLE IF NOT EXISTS `ObjectPageUrl` (
  `ObjectId` int(11) unsigned NOT NULL,
  `Url` varchar(300) CHARACTER SET utf8 DEFAULT NULL,
  `SiteId` int(11) NOT NULL,
  PRIMARY KEY (`ObjectId`),
  UNIQUE KEY `UX__Path_SiteId__ObjectPageUrl` (`Url`,`SiteId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Здесь хранятся адреса для всех указанных страниц';

-- Dumping data for table reviews.ObjectPageUrl: 8 rows
/*!40000 ALTER TABLE `ObjectPageUrl` DISABLE KEYS */;
INSERT IGNORE INTO `ObjectPageUrl` (`ObjectId`, `Url`, `SiteId`) VALUES
	(3, '404', 1),
	(1, '', 1),
	(64, 'about', 1),
	(63, 'contacts', 1),
	(65, 'map', 1),
	(103, 'catalog', 1),
	(104, 'catalog/1', 1),
	(105, 'catalog/2', 1);
/*!40000 ALTER TABLE `ObjectPageUrl` ENABLE KEYS */;


-- Dumping structure for table reviews.Relation
CREATE TABLE IF NOT EXISTS `Relation` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Page` int(11) unsigned NOT NULL,
  `Node` int(11) unsigned NOT NULL,
  `Level` int(5) unsigned NOT NULL DEFAULT '0',
  `Type` int(3) unsigned NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UX__Node_Page_Type__Relation` (`Node`,`Page`,`Type`),
  KEY `IX_Node_Relation` (`Node`),
  KEY `IX_Page_Relation` (`Page`)
) ENGINE=MyISAM AUTO_INCREMENT=399 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Связь объектов между собой';

-- Dumping data for table reviews.Relation: 17 rows
/*!40000 ALTER TABLE `Relation` DISABLE KEYS */;
INSERT IGNORE INTO `Relation` (`Id`, `Page`, `Node`, `Level`, `Type`) VALUES
	(41, 1, 0, 0, 1),
	(44, 3, 1, 0, 1),
	(45, 3, 0, 1, 1),
	(206, 63, 1, 0, 1),
	(207, 63, 0, 1, 1),
	(208, 64, 1, 0, 1),
	(209, 64, 0, 1, 1),
	(210, 65, 1, 0, 1),
	(211, 65, 0, 1, 1),
	(391, 104, 103, 0, 1),
	(390, 103, 0, 1, 1),
	(394, 105, 103, 0, 1),
	(393, 104, 1, 1, 1),
	(389, 103, 1, 0, 1),
	(396, 105, 1, 1, 1),
	(395, 105, 0, 2, 1),
	(392, 104, 0, 2, 1);
/*!40000 ALTER TABLE `Relation` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
