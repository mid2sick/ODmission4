from CrawlerAPI.Crawlers.MetadataCrawlerAHCMS import MetadataCrawlerAHCMS
from CrawlerAPI.Crawlers.MetadataCrawlerAHTWH import MetadataCrawlerAHTWH
from CrawlerAPI.Crawlers.MetadataCrawlerNDAP import MetadataCrawlerNDAP
from CrawlerAPI.Crawlers.MetadataCrawlerTLCDA import MetadataCrawlerTLCDA

def GetCrawlers():
	crawlers = []

	crawlers.append(MetadataCrawlerAHCMS())
	crawlers.append(MetadataCrawlerAHTWH())
	crawlers.append(MetadataCrawlerNDAP())
	crawlers.append(MetadataCrawlerTLCDA())
	### Add new class here!

	return crawlers

def GetCrawler(webAbbr):
	crawlers = GetCrawlers()

	for crawler in crawlers:
		if webAbbr==crawler.webAbbr:
			return crawler

	print("Wrong webAbbr input")
	return None

