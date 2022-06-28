from CrawlerAPI.MetadataCrawlerAPI import Crawling
import argparse
import sys

if __name__ == '__main__':
	parser = argparse.ArgumentParser()
	parser.add_argument("inputID", type=str)
	parser.add_argument("inputWebAbbr", type=str)
	args = parser.parse_args()
	### get ids
	#ids = InputToIDs(args.inputWebAbbr, args.inputFileName)
	#if ids==False:
	#	print("wrong input")
	#	sys.exit()
	### use id to crawl (should be crawling by using multi-threading)
	#for item in ids:
	success = Crawling(args.inputWebAbbr, args.inputID)
	print(success)