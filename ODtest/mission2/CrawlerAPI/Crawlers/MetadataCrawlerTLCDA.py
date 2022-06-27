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

WEBABBR = "TLCDA"
WEBURL = "https://journal.th.gov.tw/query.php"
ROW_WITH_DATA = 4
ID_COL = 5
WAITTIME = 10

class MetadataCrawlerTLCDA(MetadataCrawlerBase):
	def __init__(self):
		super().__init__(WEBABBR, WEBURL)

	def IndentifyCSV(self, filePath):
		infile = open(filePath, 'r', encoding = 'utf-8-sig')
		rows = csv.reader(infile, delimiter=',')

		for row in rows:
			if row[0] == "地方議會議事錄-資料目錄":
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
		# no need to crawl since "csv downloaded from website" contains more info than that can be crawled on website.
		return {"id":idIn}

	def DataCleaning(self, strIn):
		return strIn

	def DataLinking(self, dictIn):
		return ["@"]