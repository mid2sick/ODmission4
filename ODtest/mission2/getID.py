try:
	from CrawlerAPI.MetadataCrawlerAPI import InputToIDs
except:
	print("import fail")
	sys.exit()
# except ImportError:
#     raise ImportError('fail to import InputToIDs')

import argparse
import sys

if __name__ == '__main__':
	parser = argparse.ArgumentParser()
	parser.add_argument("inputFileName", type=str)
	parser.add_argument("inputWebAbbr", type=str)
	args = parser.parse_args()
	### get ids
	ids = InputToIDs(args.inputWebAbbr, args.inputFileName)
	if ids==False:
		print("wrong input<br>")
		sys.exit()
	print(ids)