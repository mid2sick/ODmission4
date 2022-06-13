class MetadataCrawlerBase:
	def __init__(self, _webAbbr, _webUrl):
		self.webAbbr = _webAbbr
		self.webUrl = _webUrl

	def IndentifyCSV(self, filePath):
		return False

	def InputToIDs(self, filePath):
		return []

	def IDCrawling(self, idIn):
		return {}

	def DataCleaning(self, strIn):
		return strIn

	def DataLinking(self, dictIn):
		return ["@"]
		### Use the value "@" for the data that is not used
