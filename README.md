#Spark Fuel Package

Documentation is coming soon. But for now visit http://spark.bencorlett.com to see a demo or https://github.com/bencorlett/Spark-Demo to download the source.

## Installing

* Download from github by clicking [here](https://github.com/bencorlett/Spark/tarball/1.0/master).
* Install using git:

        git submodule add https://github.com/bencorlett/spark fuel/packages/spark

* Enable in APPPATH/config/config.php _(around line 187)_:

        'packages' => array(
            'spark',
            'orm',    // If you intend on using the orm as a driver
        ),
* Go to http://spark.bencorlett.com and learn how to use it.