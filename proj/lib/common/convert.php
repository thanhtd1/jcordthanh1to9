<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");

/**
 *
 * オプションで使用する文字列
 * r: 「全角」英字を「半角」に変換します。
 * R: 「半角」英字を「全角」に変換します。
 * n: 「全角」数字を「半角」に変換します。
 * N: 「半角」数字を「全角」に変換します。
 * a: 「全角」英数字記号を「半角」に変換します。
 * A: 「半角」英数字記号を「全角」に変換します。
 * s: 「全角」スペースを「半角」に変換します（U+3000 -> U+0020）。
 * S: 「半角」スペースを「全角」に変換します（U+0020 -> U+3000）。
 * k: 「半角カタカナ」を「全角カタカナ」に変換します。
 * K: 「半角カタカナ」を「全角カタカナ」に変換します。
 * h: 「半角カタカナ」を「全角ひらがな」に変換します。
 * H: 「全角ひらがな」を「全角カタカナ」に変換します。
 * c: 「全角カタカナ」を「全角ひらがな」に変換します。
 * C: 「全角ひらがな」を「全角カタカナ」に変換します。
 * v: 「う濁」を「は濁」に変換します。
 * V: 「ウ濁」を「ハ濁」に変換します。
 * Q: 「半角」クォーテーション、「半角」アポストロフィを「全角」に変換します。
 * q: 「全角」クォーテーション、「全角」アポストロフィを「半角」に変換します。
 * B: 「半角」バックスラッシュを「全角」に変換します。
 * b: 「全角」バックスラッシュを「半角」に変換します。
 * T: 「半角」チルダを「全角」にチルダ変換します。
 * t: 「全角」チルダを「半角」チルダに変換します。
 * W: 全角「波ダッシュ」を全角「チルダ」に変換します。
 * w: 全角「チルダ」を全角「波ダッシュ」に変換します。
 * P: 「ハイフン、ダッシュ、マイナス」を「全角ハイフンマイナス」に変換します。（U+FF0D）
 * p: 「ハイフン、ダッシュ、マイナス」を「半角ハイフンマイナス」に変換します。（U+002D）
 * U: 「U+0021」～「U+007E」以外の「半角」記号を「全角」記号に変換します。
 * u: 「U+0021」～「U+007E」以外の「全角」記号を「半角」記号に変換します。
 * X: 「カッコ付き文字」を「半角括弧と中の文字」に展開します。
 * Y: 集合文字を展開します。（単位文字以外）
 * Z: 小字形文字を大文字に変換します。（U+FE50～U+FE6B）
 *
 * @param String $str 変換する文字列
 * @param String $opt 変換オプション
 *
 * @return String 変換された文字列
 */
function convertKana($str = '', $opt = '')
{
	// 変換する文字・オプションが文字列でない場合はそのまま返す
	if (!is_string($str) or ! is_string($opt)) {
		return $str;
	}

	/** ------------------------------------------------------------------------
	 * ここからオプションの文字により変換を行う関数です。
	 * ---------------------------------------------------------------------- */
	$convert = function($s) use(&$str) {
		switch ($s) {
			// r: 「全角」英字を「半角」に変換します。
			case 'r':
				$str = mb_convert_kana($str, 'r');
				break;

			// R: 「半角」英字を「全角」に変換します。
			case 'R':
				$str = mb_convert_kana($str, 'R');
				break;

			// n: 「全角」数字を「半角」に変換します。
			case 'n':
				$str = mb_convert_kana($str, 'n');
				break;

			// N: 「半角」数字を「全角」に変換します。
			case 'N':
				$str = mb_convert_kana($str, 'N');
				break;

			// a: 「全角」英数字記号を「半角」に変換します。
			//
			// "a", "A" オプションに含まれる文字は、
			// U+0022, U+0027, U+005C, U+007Eを除く（" ' \ ~ ）
			// U+0021 - U+007E の範囲です。
			case 'a':
				$str = mb_convert_kana($str, 'a');
				break;

			// A: 「半角」英数字記号を「全角」に変換します 。
			//
			// "a", "A" オプションに含まれる文字は、
			// U+0022, U+0027, U+005C, U+007Eを除く（" ' \ ~ ）
			// U+0021 - U+007E の範囲です。
			case 'A':
				$str = mb_convert_kana($str, 'A');
				break;

			// s: 「全角」スペースを「半角」に変換します（U+3000 -> U+0020）。
			case 's':
				$str = mb_convert_kana($str, 's');
				break;

			// S: 「半角」スペースを「全角」に変換します（U+0020 -> U+3000）。
			case 'S':
				$str = mb_convert_kana($str, 'S');
				break;

			case 'k':
				// 全角カタカナを半角カタカナに変換します。
				$str = mb_convert_kana($str, 'kv');
				break;

			case 'K':
				// 半角カタカナを全角カタカナに変換します。
				$str = mb_convert_kana($str, 'KV');
				break;

			case 'h':
				// 全角ひらがなを半角カタカナに変換します。
				$str = mb_convert_kana($str, 'hv');
				break;

			case 'H':
				// 半角カタカナを全角ひらがなに変換します。
				$str = mb_convert_kana($str, 'HV');
				break;

			// c: 「全角カタカナ」を「全角ひらがな」に変換します。
			//
			// 「ヽヾ」は「ゝゞ」に変換されます。
			// 「ヴ」は「う゛」に展開されます。
			// 「ヶ」は変換されません。（変換先が「か」「が」「こ」の複数あるため）
			// 「ヵ」は「か」に変換されます。
			case 'c':
				$str = mb_convert_kana($str, 'c');
				$kana = array('ヴ', 'ヵ', 'ヽ', 'ヾ');
				$hira = array('う゛', 'か', 'ゝ', 'ゞ');
				$str = str_replace($kana, $hira, $str);
				break;

			// C: 「全角ひらがな」を「全角カタカナ」に変換します。
			//
			// 「ゝゞ」は「ヽヾ」に変換されます。
			// 「う゛」は「ヴ」に結合されます。
			case 'C':
				$str = mb_convert_kana($str, 'C');
				$hira = array('ウ゛', 'ゝ', 'ゞ');
				$kana = array('ヴ', 'ヽ', 'ヾ');
				$str = str_replace($hira, $kana, $str);
				break;

			// v: 「う濁」を「は濁」に変換します。
			//
			// 「う゛ぁ」「う゛ぃ」「う゛」「う゛ぇ」「う゛ぉ」を
			// 「ば」「び」「ぶ」「べ」「ぼ」に変換します。
			case 'v':
				$udaku = array(
					'う゛ぁ', 'う゛ぃ', 'う゛ぇ', 'う゛ぉ', 'う゛',
					'ゔぁ', 'ゔぃ', 'ゔぇ', 'ゔぉ', 'ゔ'
				);
				$hadaku = array(
					'ば', 'び', 'べ', 'ぼ', 'ぶ',
					'ば', 'び', 'べ', 'ぼ', 'ぶ'
				);
				$str = str_replace($udaku, $hadaku, $str);
				break;

			// V: 「ウ濁」を「ハ濁」に変換します。
			//
			// 「ヴァ」「ヴィ」「ヴ」「ヴェ」「ヴォ」を
			// 「バ」「ビ」「ブ」「ベ」「ボ」に変換します。
			case 'V':
				$udaku = array(
					'ウ゛ァ', 'ウ゛ィ', 'ウ゛ェ', 'ウ゛ォ', 'ウ゛',
					'ヴァ', 'ヴィ', 'ヴェ', 'ヴォ', 'ヴ'
				);
				$hadaku = array(
					'バ', 'ビ', 'ベ', 'ボ', 'ブ',
					'バ', 'ビ', 'ベ', 'ボ', 'ブ'
				);
				$str = str_replace($udaku, $hadaku, $str);
				break;

			// Q: 半角クォーテーション、半角アポストロフィを全角に変換します。
			case 'Q':
				$han = array('"', "'");
				$zen = array('＂', '＇');
				$str = str_replace($han, $zen, $str);
				break;

			// q: 全角クォーテーション、全角アポストロフィを半角に変換します。
			case 'q':
				$han = array('"', "'");
				$zen = array('＂', '＇');
				$str = str_replace($zen, $han, $str);
				break;

			// B: 半角バックスラッシュを全角に変換します。
			case 'B':
				$han = "\\";
				$zen = '＼';
				$str = str_replace($han, $zen, $str);
				break;

			// b: 全角バックスラッシュを半角に変換します。
			case 'b':
				$han = "\\";
				$zen = '＼';
				$str = str_replace($zen, $han, $str);
				break;

			// T: 半角チルダを全角にチルダ変換します。
			case 'T':
				$han = '~';
				$zen = '～';
				$str = str_replace($han, $zen, $str);
				break;

			// t: 全角チルダを半角チルダに変換します。
			case 't':
				$han = '~';
				$zen = '～';
				$str = str_replace($zen, $han, $str);
				break;

			// W: 全角波ダッシュを全角チルダに変換します。
			case 'W':
				$nami = '〜';
				$tilde = '～';
				$str = str_replace($nami, $tilde, $str);
				break;

			// w: 全角チルダを全角波ダッシュに変換します。
			case 'w':
				$nami = '〜';
				$tilde = '～';
				$str = str_replace($tilde, $nami, $str);
				break;

			// P: ハイフン、ダッシュ、マイナスを全角ハイフンマイナスに変換します。（U+FF0D）
			//	英数記号の後ろにある全角・半角長音符も含む
			//
			// http://hydrocul.github.io/wiki/blog/2014/1101-hyphen-minus-wave-tilde.html
			//	「U+002D」半角ハイフンマイナス
			//	「U+FE63」小さいハイフンマイナス。NFKD/NFKC正規化で U+002D
			//	「U+FF0D」全角ハイフンマイナス
			//	「U+2212」「U+207B」「U+208B」マイナス
			//	「U+2010」「U+2011」ハイフン
			//	「U+2012」～「U+2015」「U+FE58」ダッシュ
			case 'P':
				$phyhen = array(
					'-', '﹣', '－', '−', '⁻', '₋',
					'‐', '‑', '‒', '–', '—', '―', '﹘'
				);
				$change = '－';
				$str = str_replace($phyhen, $change, $str);
				$str = preg_replace('/([!-~！-～])(ー|ｰ)/u', '$1' . $change, $str);
				break;

			// p: ハイフン、ダッシュ、マイナスを半角ハイフンマイナスに変換します。（U+002D）
			//	英数記号の後ろにある全角・半角長音符も含む
			//
			// http://hydrocul.github.io/wiki/blog/2014/1101-hyphen-minus-wave-tilde.html
			//	「U+002D」半角ハイフンマイナス
			//	「U+FE63」小さいハイフンマイナス。NFKD/NFKC正規化で U+002D
			//	「U+FF0D」全角ハイフンマイナス
			//	「U+2212」「U+207B」「U+208B」マイナス
			//	「U+2010」「U+2011」ハイフン
			//	「U+2012」～「U+2015」「U+FE58」ダッシュ
			case 'p':
				$phyhen = array(
					'-', '﹣', '－', '−', '⁻', '₋',
					'‐', '‑', '‒', '–', '—', '―', '﹘'
				);
				$change = '-';
				$str = str_replace($phyhen, $change, $str);
				$str = preg_replace('/([!-~！-～])(ー|ｰ)/u', '$1' . $change, $str);
				break;

			// U: 「U+0021」～「U+007E」以外の「半角」記号を「全角」記号に変換します。
			//
			// http://www.asahi-net.or.jp/~ax2s-kmtn/ref/unicode/uff00.html
			case 'U':
				$han = array(
					'⦅', '⦆', '¢', '£', '¬', '¯', '¦', '¥',
					'₩', '￨', '￩', '￪', '￫', '￬', '￭', '￮'
				);
				$zen = array(
					'｟', '｠', '￠', '￡', '￢', '￣', '￤', '￥',
					'￦', '│', '←', '↑', '→', '↓', '■', '○'
				);
				$str = str_replace($han, $zen, $str);
				break;

			// u: 「U+0021」～「U+007E」以外の「全角」記号を「半角」記号に変換します。
			//
			// http://www.asahi-net.or.jp/~ax2s-kmtn/ref/unicode/uff00.html
			case 'u':
				$han = array(
					'⦅', '⦆', '¢', '£', '¬', '¯', '¦', '¥',
					'₩', '￨', '￩', '￪', '￫', '￬', '￭', '￮'
				);
				$zen = array(
					'｟', '｠', '￠', '￡', '￢', '￣', '￤', '￥',
					'￦', '│', '←', '↑', '→', '↓', '■', '○'
				);
				$str = str_replace($zen, $han, $str);
				break;

			// X: カッコ付き文字を半角括弧と中の文字に展開します。
			//
			// http://www.asahi-net.or.jp/~ax2s-kmtn/ref/unicode/u2460.html
			// http://www.asahi-net.or.jp/~ax2s-kmtn/ref/unicode/u3200.html
			case 'X':
				$single = array(
					'⑴', '⑵', '⑶', '⑷', '⑸',
					'⑹', '⑺', '⑻', '⑼', '⑽',
					'⑾', '⑿', '⒀', '⒁', '⒂',
					'⒃', '⒄', '⒅', '⒆', '⒇',
					'⒜', '⒝', '⒞', '⒟', '⒠', '⒡', '⒢', '⒣',
					'⒤', '⒥', '⒦', '⒧', '⒨', '⒩', '⒪', '⒫',
					'⒬', '⒭', '⒮', '⒯', '⒰', '⒱', '⒲', '⒳',
					'⒴', '⒵',
					'㈠', '㈡', '㈢', '㈣', '㈤',
					'㈥', '㈦', '㈧', '㈨', '㈩',
					'㈪', '㈫', '㈬', '㈭', '㈮', '㈯', '㈰',
					'㈱', '㈲', '㈳', '㈴', '㈵', '㈶', '㈷',
					'㈸', '㈹', '㈺', '㈻', '㈼', '㈽', '㈾',
					'㈿', '㉀', '㉁', '㉂', '㉃'
				);
				$multi = array(
					'(1)', '(2)', '(3)', '(4)', '(5)',
					'(6)', '(7)', '(8)', '(9)', '(10)',
					'(11)', '(12)', '(13)', '(14)', '(15)',
					'(16)', '(17)', '(18)', '(19)', '(20)',
					'(a)', '(b)', '(c)', '(d)', '(e)', '(f)', '(g)', '(h)',
					'(i)', '(j)', '(k)', '(l)', '(m)', '(n)', '(o)', '(p)',
					'(q)', '(r)', '(s)', '(t)', '(u)', '(v)', '(w)', '(x)',
					'(y)', '(z)',
					'(一)', '(二)', '(三)', '(四)', '(五)',
					'(六)', '(七)', '(八)', '(九)', '(十)',
					'(月)', '(火)', '(水)', '(木)', '(金)', '(土)', '(日)',
					'(株)', '(有)', '(社)', '(名)', '(特)', '(財)', '(祝)',
					'(労)', '(代)', '(呼)', '(学)', '(監)', '(企)', '(資)',
					'(協)', '(祭)', '(休)', '(自)', '(至)'
				);
				$str = str_replace($single, $multi, $str);
				break;

			// Y: 集合文字を展開します。（単位文字以外）
			//
			// http://www.asahi-net.or.jp/~ax2s-kmtn/ref/unicode/u2460.html
			// http://www.asahi-net.or.jp/~ax2s-kmtn/ref/unicode/u3200.html
			// http://www.asahi-net.or.jp/~ax2s-kmtn/ref/unicode/u3300.html
			case 'Y':
				$single = array(
					'㌀', '㌁', '㌂', '㌃', '㌄', '㌅',
					'㌆', '㌇', '㌈', '㌉', '㌊', '㌋',
					'㌌', '㌍', '㌎', '㌏', '㌐', '㌑', '㌒',
					'㌓', '㌔', '㌕', '㌖', '㌗', '㌘',
					'㌙', '㌚', '㌛', '㌜', '㌝', '㌞',
					'㌟', '㌠', '㌡', '㌢', '㌣', '㌤',
					'㌥', '㌦', '㌧', '㌨', '㌩', '㌪', '㌫',
					'㌬', '㌭', '㌮', '㌯', '㌰', '㌱', '㌲',
					'㌳', '㌴', '㌵', '㌶', '㌷', '㌸',
					'㌹', '㌺', '㌻', '㌼', '㌽', '㌾', '㌿',
					'㍀', '㍁', '㍂', '㍃', '㍄', '㍅', '㍆',
					'㍇', '㍈', '㍉', '㍊', '㍋', '㍌',
					'㍍', '㍎', '㍏', '㍐', '㍑', '㍒', '㍓',
					'㍔', '㍕', '㍖', '㍗',
					'㍿', '㍻', '㍼', '㍽', '㍾',
					'㋀', '㋁', '㋂', '㋃', '㋄', '㋅',
					'㋆', '㋇', '㋈', '㋉', '㋊', '㋋',
					'㏠', '㏡', '㏢', '㏣', '㏤',
					'㏥', '㏦', '㏧', '㏨', '㏩',
					'㏪', '㏫', '㏬', '㏭', '㏮',
					'㏯', '㏰', '㏱', '㏲', '㏳',
					'㏴', '㏵', '㏶', '㏷', '㏸',
					'㏹', '㏺', '㏻', '㏼', '㏽', '㏾',
					'㍘', '㍙', '㍚', '㍛', '㍜', '㍝',
					'㍞', '㍟', '㍠', '㍡', '㍢',
					'㍣', '㍤', '㍥', '㍦', '㍧',
					'㍨', '㍩', '㍪', '㍫', '㍬',
					'㍭', '㍮', '㍯', '㍰',
					'⒈', '⒉', '⒊', '⒋', '⒌', '⒍', '⒎', '⒏', '⒐', '⒑',
					'⒒', '⒓', '⒔', '⒕', '⒖', '⒗', '⒘', '⒙', '⒚', '⒛',
					'№', '℡', '㏍', '㏇', '㏂', '㏘'
				);
				$multi = array(
					'アパート', 'アルファ', 'アンペア', 'アール', 'イニング', 'インチ',
					'ウォン', 'エスクード', 'エーカー', 'オンス', 'オーム', 'カイリ',
					'カラット', 'カロリー', 'ガロン', 'ガンマ', 'ギガ', 'ギニー', 'キュリー',
					'ギルダー', 'キロ', 'キログラム', 'キロメートル', 'キロワット', 'グラム',
					'グラムトン', 'クルゼイロ', 'クローネ', 'ケース', 'コルナ', 'コーポ',
					'サイクル', 'サンチーム', 'シリング', 'センチ', 'セント', 'ダース',
					'デシ', 'ドル', 'トン', 'ナノ', 'ノット', 'ハイツ', 'パーセント',
					'パーツ', 'バーレル', 'ピアストル', 'ピクル', 'ピコ', 'ビル', 'ファラッド',
					'フィート', 'ブッシェル', 'フラン', 'ヘクタール', 'ペソ', 'ペニヒ',
					'ヘルツ', 'ペンス', 'ページ', 'ベータ', 'ポイント', 'ボルト', 'ホン',
					'ポンド', 'ホール', 'ホーン', 'マイクロ', 'マイル', 'マッハ', 'マルク',
					'マンション', 'ミクロン', 'ミリ', 'ミリバール', 'メガ', 'メガトン',
					'メートル', 'ヤード', 'ヤール', 'ユアン', 'リットル', 'リラ', 'ルピー',
					'ルーブル', 'レム', 'レントゲン', 'ワット',
					'株式会社', '平成', '昭和', '大正', '明治',
					'1月', '2月', '3月', '4月', '5月', '6月',
					'7月', '8月', '9月', '10月', '11月', '12月',
					'1日', '2日', '3日', '4日', '5日',
					'6日', '7日', '8日', '9日', '10日',
					'11日', '12日', '13日', '14日', '15日',
					'16日', '17日', '18日', '19日', '20日',
					'21日', '22日', '23日', '24日', '25日',
					'26日', '27日', '28日', '29日', '30日', '31日',
					'0点', '1点', '2点', '3点', '4点', '5点',
					'6点', '7点', '8点', '9点', '10点',
					'11点', '12点', '13点', '14点', '15点',
					'16点', '17点', '18点', '19点', '20点',
					'21点', '22点', '23点', '24点',
					'1.', '2.', '3.', '4.', '5.', '6.', '7.', '8.', '9.', '10.',
					'11.', '12.', '13.', '14.', '15.', '16.', '17.', '18.', '19.',
					'20.',
					'No.', 'TEL', 'K.K.', 'Co.', 'a.m.', 'p.m.'
				);
				$str = str_replace($single, $multi, $str);
				break;

			// Z: 小字形文字を大文字に変換します。（U+FE50～U+FE6B）
			// 「﹐﹑﹒﹔﹕﹖﹗﹘﹙﹚﹛﹜﹝﹞﹟﹠﹡﹢﹣﹤﹥﹦﹨﹩﹪﹫」
			//
			// 「U+FF58」は「U+2014」へマッピングされていますが、揺らぎの訂正のため
			// 「U+002D（半角ハイフンマイナス）」に変換します。
			//
			// http://www.asahi-net.or.jp/~ax2s-kmtn/ref/unicode/ufe50.html
			case 'Z':
				$small = array(
					'﹐', '﹑', '﹒', '﹔', '﹕', '﹖', '﹗', '﹘', '﹙', '﹚',
					'﹛', '﹜', '﹝', '﹞', '﹟', '﹠', '﹡', '﹢', '﹣',
					'﹤', '﹥', '﹦', '﹨', '﹩', '﹪', '﹫'
				);
				$big = array(
					',', '、', '.', ';', ':', '?', '!', '-', '(', ')',
					'{', '}', '〔', '〕', '#', '&', '*', '+', '-',
					'<', '>', '=', "\\", '$', '%', '@'
				);
				$str = str_replace($small, $big, $str);
				break;
			default :
				break;
		}
	};

	// オプション文字列を分解して一文字ごとに$convertを実行します
	array_map($convert, str_split($opt));

	return $str;
}


?>