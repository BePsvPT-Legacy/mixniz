SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 資料庫： `esimtw`
--

-- --------------------------------------------------------

--
-- 資料表結構 `file_upload_info`
--

CREATE TABLE IF NOT EXISTS `file_upload_info` (
`id` int(11) NOT NULL,
  `hash_key` varchar(96) COLLATE utf8_unicode_ci NOT NULL,
  `file_upload_user` int(11) NOT NULL,
  `file_name` varchar(96) COLLATE utf8_unicode_ci NOT NULL,
  `file_extension_name` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_size` int(11) NOT NULL,
  `file_type` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `file_upload_time` int(11) NOT NULL,
  `file_upload_ip` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `file_upload_info`
--
ALTER TABLE `file_upload_info`
 ADD PRIMARY KEY (`id`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `file_upload_info`
--
ALTER TABLE `file_upload_info`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
