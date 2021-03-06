import csv
import json
import requests
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.options import Options
from bs4 import BeautifulSoup

from CrawlerAPI.Crawlers.MetadataCrawlerBase import MetadataCrawlerBase
import CrawlerAPI.Crawlers.MetadataCrawlerUtils as MetadataCrawlerUtils

WEBABBR = "AHCMS"
WEBURL = "https://ahonline.drnh.gov.tw/index.php?act=Archive"
ROW_WITH_DATA = 3
ID_COL = 7
WAITTIME = 10

class MetadataCrawlerAHCMS(MetadataCrawlerBase):
	def __init__(self):
		super().__init__(WEBABBR, WEBURL)

	def IndentifyCSV(self, filePath):
		infile = open(filePath, 'r', encoding = 'utf-8-sig')
		rows = csv.reader(infile, delimiter=',')

		for row in rows:
			if row[0] == "國史館檔案史料文物查詢系統-資料目錄":
				return True
			else:
				break
	
		return False

	def InputToIDs(self, filePath):
		infile = open(filePath, 'r', encoding = 'utf-8-sig')
		rows = csv.reader(infile, delimiter=',')
		ids = []
		i=0
		for row in rows:
			if i<=ROW_WITH_DATA:
				i+=1
				continue
			ids.append(row[ID_COL])

		return ids

	def IDCrawling(self, idIn):
		chrome_options = Options() 
		chrome_options.add_argument('--headless')
		chrome_options.add_argument('--disable-gpu')
		browser = webdriver.Chrome(executable_path='C:\\WebRoot\\OD\\ODmission4\\ODtest\\102\\chromedriver.exe', options=chrome_options)
		browser.get(self.webUrl)

		search = MetadataCrawlerUtils.WaitFindElement(browser, WAITTIME, By.ID, "search_input")
		search.send_keys(idIn)
		btn = browser.find_element(By.ID, "search_submit")
		btn.click()

		ahref = MetadataCrawlerUtils.WaitFindElement(browser, WAITTIME, By.XPATH, "/html/body/div[2]/div[2]/div[4]/div[3]/div/div[2]/div[1]/div[2]/div[1]/div[last()]/span[2]/span/a")
		ahref.click()
		browser.switch_to.window(browser.window_handles[1])
		metaURL = MetadataCrawlerUtils.WaitGetURL(browser, WAITTIME)
		browser.quit()

		response = requests.get(metaURL)
		soup = BeautifulSoup(response.text, "html.parser")

		res = {}
		table = soup.find("table", class_="meta_table").find_all("tr")
		for tr in table:
			try:
				field = tr.find(class_="meta_field")
				value = tr.find(class_="meta_value")
				res[field.get_text()] = value.get_text()
			except Exception as e:
				pass

		res['url'] = metaURL

		return res

	def DataCleaning(self, strIn):
		return strIn.replace(" ", "")

	def DataLinking(self, dictIn):
		infile = open("C:\WebRoot\OD\ODmission4\ODtest\mission2\CrawlerAPI\Crawlers\DataLinkAHCMS.csv", 'r', encoding = 'utf-8-sig')
		rows = csv.reader(infile, delimiter=',')

		tmpDict = {}
		maxLength = 6-1
		for row in rows:
			index = int(row[1])-1
			if index==-2:
				continue

			if (index+1) > maxLength:
				maxLength = index+1
			tmpDict[row[0]] = index
			
		res = ["@"]*maxLength
		for key, value in tmpDict.items():
			tmp = dictIn.get(key)
			if tmp==None:
				continue

			if value in [15-1, 16-1, 18-1]:
				tmp = tmp.replace('，', ";")
			res[value] = tmp

		res[14-1] = json.dumps(dictIn, ensure_ascii=False)
		res[12-1] = dictIn.get('url')
		
		return res