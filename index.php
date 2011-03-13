<?php
	require_once("settings.php");
	require_once("googleVoice.php");
	class gvTest extends GoogleVoice
	{
		public function inbox()
		{
			return $this->callMethod("inbox");
		}
		public function starred()
		{
			return $this->callMethod("starred");
		}
		public function all()
		{
			return $this->callMethod("all");
		}
		protected function callMethod($method)
		{
			$url = "https://www.google.com/voice/inbox/recent/$method/";
			$raw = $this->curl($url);
			return $this->threadParse($raw);
		}
		protected function threadParse($raw)
		{
			$xml = new SimpleXMLElement($raw);
			$json = $xml->json;
			$html = $this->ampsfix($xml->html);

			$dom = new DOMDocument();
			@$dom-> loadHTML($html);
			$dom-> normalizeDocument();

			$xpath = new DOMXPath($dom);

			$array = array();

				$imgs = $xpath->evaluate("/html/body//div/div/div/table/tr/td/div/table/tr/td/table/tr/td/div/img");
				$i = 0;
				foreach($imgs as $img)
				{
					$t = explode("?",$img->getAttribute("src"));
					$array[$i]["user"]["avatar"] = $t[0];
					$i++;
				}

				$ppl = $xpath->evaluate("/html/body//div/div/div/table/tr/td/div/table/tr/td/table/tr/td/div/span/a");
				$i = 0;
				foreach($ppl as $pp)
				{
					$t = $pp->nodeValue;
					$array[$i]["user"]["name"] = $t;
					$i++;
				}
				

			return $array;
		}

		protected function ampsfix($str)
		{
			$str = html_entity_decode($str);
			$str = str_replace("&","&nbsp;",$str);

			return $str;
		}
	}
	try
	{
		$gv = new gvTest(Settings::USERNAME,Settings::PASSWORD,TRUE);
		$array = $gv->all();
		foreach($array as $thread)
		{
			?><img src="http://www.google.com<?= $thread["user"]["avatar"] ?>" title='<?= $thread["user"]["name"] ?>' /><?php
		}
		$gv->sms(Settings::TEST_NUMBER,Settings::TEST_MSG);
	} catch(Exception $e)
	{
		echo $e,"<br />","<pre>Last HTML:<br />",$gv->lastHTML,"</pre>";
	}
?>
