from CrawlerAPI.Crawlers.MetadataCrawlers import GetCrawler, GetCrawlers
from CrawlerAPI.DBRelated.MetadataCrawlerDBHandler import CheckIsDataCrawlered, UpdataDB
import json
import chromedriver_autoinstaller

def IdentifyWebAbbr(filePath):
	crawlers = GetCrawlers()
	for crawler in crawlers:
		if crawler.IndentifyCSV(filePath):
			return crawler.webAbbr

	return False

def InputToIDs(webAbbr, filePath, isReturnJson=True):
	### Get Crawler
	crawler = GetCrawler(webAbbr)
	if crawler==None: # Wrong webAbbr input
		return False

	### Get ids in csv
	ids=None
	try:
		ids = crawler.InputToIDs(filePath)
	except Exception as e:
		# print(f"Error: {e}")
		print("Get ids fails")
		return False

	### convert to json (for better output for php to use)
	res = {}
	for i in range(len(ids)):
		res[i] = ids[i]
		
	if isReturnJson:
		return json.dumps(res)
	return res

def Crawling(webAbbr, inputID):
	### Check if data is crawlered
	checkNum = CheckIsDataCrawlered(webAbbr, inputID)
	if checkNum==1: # has crawlered
		return True
	elif checkNum==0: # no crawlered
		pass
	else: # error
		return False

	### Get Crawler
	crawler = GetCrawler(webAbbr)
	if crawler==None: # Wrong webAbbr input
		return False
	
	### Check if chromedriver is updated
	chromedriver_autoinstaller.install()
	
	### Crawling
	tmpDict=None
	try:
		tmpDict = crawler.IDCrawling(inputID)
	except Exception as e:
		# print(f"Error: {e}")
		print("Crawling fails")
		return False

	### Data cleaning
	tmpDictClean = {}
	for key, value in tmpDict.items():
		newKey = crawler.DataCleaning(key)
		newValue = crawler.DataCleaning(value)
		tmpDictClean[newKey] = newValue
 
 	### Data transformation for updating DB
	tmpList = crawler.DataLinking(tmpDictClean)
	
	### Updating DB
	success = UpdataDB(webAbbr, inputID, tmpList)
	return success