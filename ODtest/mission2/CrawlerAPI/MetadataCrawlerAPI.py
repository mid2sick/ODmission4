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
	checkNum, primaryKey = CheckIsDataCrawlered(webAbbr, inputID)
	if checkNum==1: # has crawlered
		return primaryKey
	elif checkNum==0: # no crawlered
		pass
	else: # error
		return -1

	### Get Crawler
	crawler = GetCrawler(webAbbr)
	if crawler==None: # Wrong webAbbr input
		return primaryKey
	print("after get a crawler")
	### Check if chromedriver is updated
	chromedriver_autoinstaller.install(cwd=True)
	print("after install chrome driver")
	### Crawling
	tmpDict=None
	try:
		print("try IDcrawling in MetadataCrawlerAPI.py")
		tmpDict = crawler.IDCrawling(inputID)
		print("Crawling ran in MetadataCrawlerAPI.py")
	except Exception as e:
		# print(f"Error: {e}")
		print("Crawling fails in MetadataCrawlerAPI.py")
		return primaryKey

	### Data cleaning
	tmpDictClean = {}
	for key, value in tmpDict.items():
		newKey = crawler.DataCleaning(key)
		newValue = crawler.DataCleaning(value)
		tmpDictClean[newKey] = newValue
 
 	### Data transformation for updating DB
	tmpList = crawler.DataLinking(tmpDictClean)
	
	### Updating DB
	UpdataDB(webAbbr, inputID, tmpList)
	return primaryKey