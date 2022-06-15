print("in crawlMetadata.py before import\n")
from CrawlerAPI.MetadataCrawlerAPI import Crawling
import argparse
import sys
print("in crawlMetadata.py fin import\n")

if __name__ == '__main__':
	parser = argparse.ArgumentParser()
	parser.add_argument("inputID", type=str)
	parser.add_argument("inputWebAbbr", type=str)
	args = parser.parse_args()
	print("get web abbr: ", args.inputWebAbbr)
	print("get id: ", args.inputID)
	### get ids
	#ids = InputToIDs(args.inputWebAbbr, args.inputFileName)
	#if ids==False:
	#	print("wrong input")
	#	sys.exit()
	### use id to crawl (should be crawling by using multi-threading)
	#for item in ids:
	success = Crawling(args.inputWebAbbr, args.inputID)
	print(success)
	print("============")


