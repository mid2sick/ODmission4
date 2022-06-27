from CrawlerAPI.MetadataCrawlerAPI import Crawling, InputToIDs, IdentifyWebAbbr
import argparse
import sys

if __name__ == '__main__':
	parser = argparse.ArgumentParser()
	parser.add_argument("inputFilePath", type=str)
	args = parser.parse_args()

	### get WebAbbr
	inputWebAbbr = IdentifyWebAbbr(args.inputFilePath)
	if inputWebAbbr==False:
		print("wrong input")
		sys.exit()

	### get ids
	ids = InputToIDs(inputWebAbbr, args.inputFilePath, False)
	if ids==False:
		print("wrong input")
		sys.exit()

	### use id to crawl (should be crawling by using multi-threading)
	for i in range(len(ids)):
		primaryKey = Crawling(inputWebAbbr, ids[i])
		print(primaryKey)
		print("============")




