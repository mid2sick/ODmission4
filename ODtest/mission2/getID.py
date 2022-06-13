print("<br>")
try:
	from mission2.CrawlerAPI.MetadataCrawlerAPI import InputToIDs
	print("fin import inputToIDs")
except:
	print("import InputToIDs failed<br><br><br>")
# except ImportError:
#     raise ImportError('fail to import InputToIDs')

import argparse
import sys

if __name__ == '__main__':
	parser = argparse.ArgumentParser()
	parser.add_argument("inputFileName", type=str)
	parser.add_argument("inputWebAbbr", type=str)
	args = parser.parse_args()
	print("get web abbr: ", args.inputWebAbbr, "<br>")
	### get ids
	ids = InputToIDs(args.inputWebAbbr, args.inputFileName)
	if ids==False:
		print("wrong input<br>")
		sys.exit()
	print(ids)