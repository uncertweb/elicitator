#getting input
args  <- commandArgs(TRUE)
lag1  <- as.numeric(args[1])
lag2  <- as.numeric(args[2])
lag3  <- as.numeric(args[3])
lag4  <- as.numeric(args[4])
lag5  <- as.numeric(args[5])
lag6  <- as.numeric(args[6])
lag7  <- as.numeric(args[7])
mu    <- as.numeric(args[8])
sigma <- as.numeric(args[9])
distribution <- args[10]


#---------------------------------------------
library(sp)
library(gstat)
library(foreign)
library(lattice)
library(maptools)
library(eeVariogram)


options(warn=-1) 
#vector of medians
lags = c(lag1,lag2,lag3,lag4,lag5,lag6,lag7)

#calculate semivariance
if (distribution == "Normal"){
semivariance = (lags^2)/(2*0.457)
}else{if (distribution == "Lognormal"){
semivariance=(log(lags)^2)/(2*0.457)}}

#study area (change here!)
area = readShapePoly("/opt/dev/e_variogram/lib/data/NL_map.shp")
dis_lags = cal.lag.int(area)


#sermivariance table
data(semivar)

#structure semi-varince table
semivar$gamma = semivariance 
semivar$dist = dis_lags

#assign proper class require for fitting
attr(semivar,"class") = c("gstatVariogram", "data.frame")

#automatically fit the variogram 
variogram <- m_autofitVariogram (semivar, area, model = c("Sph", "Exp", "Gau", "Ste", "Mat"), kappa = c(0.05, seq(0.2, 2, 0.1), 5, 10), GLS.model = NA, verbose = FALSE)

if (!is.null(variogram)){

vari_model = variogram$var_model
model = as.character(vari_model$model[2])
nugget = vari_model$psill[1]
psill  = vari_model$psill[2]
range = vari_model$range[2]
kappa = vari_model$kappa[2]


#number of simulation
N = 1

#simulation along diagonal transect
cord = t(bbox(area))
transect = SpatialLines(list(Lines(Line(cord),1)))
s = spsample(transect,500,type="regular")

if (distribution =="Normal"){ 
simulation <- uncondition_sim(mu, sigma, N, s, vari_model)
}else{
logmu = log(mu)
logsigma = log(sigma)
simulation <- uncondition_sim(logmu, logsigma, N, s, vari_model)
for (i in 1:N)
{
sim=paste("sim",as.character(i),sep="")
simulation[[sim]] = exp(simulation[[sim]])
}
}

cord1 = coordinates(s)
d = as.matrix (dist(cord1, method = "euclidean")) 
ncol = length(d[,1])
distance = d[,1]
simulation = simulation$sim1
error = 0
write(model,"")
write(nugget,"")
write(psill,"")
write(range,"")
write(kappa,"")
write(distance, "", ncolumns = ncol)
write(simulation, "", ncolumns = ncol)
write(ncol, "")
write(error, "")

}else{

error = 1
warning = 0
model = 0
nugget = 0
psill  = 0
range = 0
kappa = 0
distance = 0
simulation = 0
ncol = 0
write(warning,"")
write(model,"")
write(nugget,"")
write(psill,"")
write(range,"")
write(kappa,"")
write(distance, "")
write(simulation, "")
write(ncol, "")
write(error, "")}

