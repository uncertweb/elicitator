### Modified from SHELF 2.0 - 2/11/2010
# Fitting elicited values to different kind of pdf
# Main variables
# Lower bound: Lo
# Upper bound: Up
# Lower quartile: l
# Median: m
# Upper quartile: u

library(gstat)

args <- commandArgs(TRUE)
Lo = as.numeric(args[1])
Up = as.numeric(args[2])
l = as.numeric(args[3])
m = as.numeric(args[4])
u = as.numeric(args[5])

# 1 Shared functions for least squares fitting

betaerror<-function(x,elicited,probabilities){
a<-exp(x[1])
b<-exp(x[2])
sum((pbeta(elicited,a,b)-probabilities)^2)
}

gammaerror<-function(x,elicited,probabilities){
a<-exp(x[1])
b<-exp(x[2])
sum((pgamma(elicited,a,b)-probabilities)^2)
}

normalerror<-function(x,elicited,probabilities){
a<-x[1]
b<-exp(x[2])
sum((pnorm(elicited,a,b)-probabilities)^2)
}

lnormerror<-function(x,elicited,probabilities){
a<-x[1]
b<-exp(x[2])
sum((plnorm(elicited,a,b)-probabilities)^2)
}

logterror<-function(x,elicited,probabilities,degreesfreedom){
a<-x[1]
b<-exp(x[2])
sum((pt((log(elicited)-a)/b,degreesfreedom)-probabilities)^2)
}

t.error<-function(x,elicited,probabilities,degreesfreedom){
a<-x[1]
b<-exp(x[2])
sum((pt((elicited-a)/b,degreesfreedom)-probabilities)^2)
}

# 2 Setting initial values
all.distributions=c("Beta","Normal","Gamma","Log normal","Log Student-t","Student-t")
degfree<-3 # for fitting student t distribution
s<-qnorm(1-(0.5/2))# getting upper quartile of standard normal distribution (with mean=0 and std=1)
v<-((u-l)/(2*s))^2 # initial with normal distribution assumption: Q3-Q1=2.s.sigma with s=0.6744898

# 3 Fit pdf
# Fit a beta distribution
m.new<-(m-Lo)/(Up-Lo)
v.new<-v/(Up-Lo)^2
elicited.new<-(c(Lo,l,m,u,Up)-Lo)/(Up-Lo)
alpha<-m.new^3/v.new*(1/m.new-1)-m.new
bet<-alpha/m.new-alpha
if ((alpha>0)&(bet>0)){
betafit<-optim(c(log(alpha),log(bet)),betaerror,elicited=c(Lo,l,m,u,Up),probabilities=c(0,0.25,0.5,0.75,1))
betaparameters<-exp(betafit$par)
betalsq<-betafit$value
distbeta<-"Beta"
}else{
betaparameters<-c(NaN,NaN)
betalsq=Inf}

# Fit a normal distribution
normalfit<-optim(c(m,0.5*log(v)),normalerror,elicited=c(Lo,l,m,u,Up),probabilities=c(0,0.25,0.5,0.75,1))
normalparameters<-c(normalfit$par[1],exp(normalfit$par[2]))
normallsq<-normalfit$value
distnorm<-"Normal"

if(Lo>=0){
# Fit a gamma distribution
gammafit<-optim(c(log(m^2/v),log(m/v)),gammaerror,elicited=c(Lo,l,m,u,Up),probabilities=c(0,0.25,0.5,0.75,1))
gammaparameters<-exp(gammafit$par)
gammalsq<-gammafit$value
distgam<-"Gamma"

# Fit a log normal distribution
std<-((log(u)-log(l))/(2*s))
lognormalfit<-optim(c(log(m),log(std)),lnormerror,elicited=c(Lo,l,m,u,Up), probabilities=c(0,0.25,0.5,0.75,1))
lnormparameters<-lognormalfit$par
lnormparameters[2]<-exp(lnormparameters[2])
lognormallsq<-lognormalfit$value
distlogn<-"Log normal"

# Fit a log t distribution
std<-((log(u)-log(l))/(2*s))
logtfit<-optim(c(log(m),log(std)),logterror,elicited=c(Lo,l,m,u,Up),probabilities=c(0,0.25,0.5,0.75,1),degreesfreedom=degfree)
logtparameters<-logtfit$par
logtparameters[2]<-exp(logtparameters[2])
logtlsq<-logtfit$value
distlogt<-"Log Student-t"}else{
gammaparameters<-NA
gammalsq<-10
lnormparameters<-NA
lognormallsq<-10
logtparameters<-NA
logtlsq<-10}

# Fit a t distribution
tfit<-optim(c(m,0.5*log(v)),t.error,elicited=c(Lo,l,m,u,Up),probabilities=c(0,0.25,0.5,0.75,1),degreesfreedom=degfree)
tparameters<-c(tfit$par[1],exp(tfit$par[2]))
tlsq<-tfit$value
diststudt<-"Student-t"
all.lsq<-c(betalsq,normallsq,gammalsq,lognormallsq,logtlsq,tlsq)

### 4 Identify best fitting pdf
x<-seq(from=Lo,to=Up,length=102)[2:101]
f.best.fitting<-rep(0,1,100)
f.beta<-1/(Up-Lo)*dbeta((x-Lo)/(Up-Lo),betaparameters[1],betaparameters[2])
 f.normal<-dnorm(x,normalparameters[1],normalparameters[2])
 if(Lo>=0){
  f.gamma<-dgamma(x,gammaparameters[1],gammaparameters[2])
  f.lognormal<-dlnorm(x,lnormparameters[1],lnormparameters[2])
  f.logt<-dt((log(x)-logtparameters[1])/logtparameters[2],degfree)/(logtparameters[2]*x)
 }
f.t<-dt((x-tparameters[1])/tparameters[2],degfree)/(tparameters[2])
f.best.fitting<-switch(which(all.lsq==min(all.lsq)),f.beta, f.normal, f.gamma, f.lognormal ,f.logt ,f.t)
best.fitting.names<-switch(which(all.lsq==min(all.lsq)),"Beta","Normal","Gamma","LogNormal","LogStudentT", "StudentT")

#
write(best.fitting.names, "")
if(best.fitting.names == "Normal") {
    parameters = normalparameters
} else if(best.fitting.names == "Beta") {
    parameters = betaparameters
} else if(best.fitting.names == "Gamma") {
    parameters = gammaparameters
} else if(best.fitting.names == "LogNormal") {
    parameters = lnormparameters
} else if(best.fitting.names == "LogStudentT") {
    parameters = logtparameters
} else if(best.fitting.names == "StudentT") {
    parameters = tparameters
}
write(parameters, "")
#write(f.best.fitting, "")