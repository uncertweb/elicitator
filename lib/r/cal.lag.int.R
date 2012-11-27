library(sp)
library(foreign)
library(lattice)
library(maptools)
library(eeVariogram)

area = readShapePoly("/opt/dev/e_variogram/lib/data/NL_map.shp")

lags = cal.lag.int(area)

write(lags[1],"")

write(lags[2],"")

write(lags[3],"")

write(lags[4],"")

write(lags[5],"")

write(lags[6],"")

write(lags[7],"")