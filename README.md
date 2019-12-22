# Dictionary-setter
A tool to help you set a dictionary values in a CSV file into a json file

======== Dictionary setter ===========
please provide these three arguments:
--src=(with the path to the source of the dictionary file)
--csv=(with the path source of the csv file with the values to put in the dictionary)
--out=(with the path to the required library to save the output in)

You can use the --dry-run flag to test the output.

The format of the CSV file shoud be as bellow:
KEY   LNG1   LNG2   LNG3
key1  val1   val2   val3
...   ...     ...   ...

The first line holds the languages of the translations
and the rest of the lines are for the key and the translations themselves.
