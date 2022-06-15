print("in getID.py")
try:
	from CrawlerAPI.MetadataCrawlerAPI import InputToIDs
	print("fin import in getID.py")
except:
	print("import fail")
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
	print("get ids in getID.py: ")
	if ids==False:
		print("wrong input<br>")
		sys.exit()
	print(ids)