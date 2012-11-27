### Modified from SHELF 2.0 - 2/11/2010

# Main variables 
# Lower bound: Lo 
# Upper bound: Up 
# Lower quartile: l 
# Median: m 
# Upper quartile: u 

args <- commandArgs(TRUE)
Lo = as.numeric(args[1])
Up = as.numeric(args[2])
l = as.numeric(args[3])
m = as.numeric(args[4])
u = as.numeric(args[5])


##########################*Calculate coefficient of skewness*#########################################################
 SK = abs((u+l-(2*m))/(u-l))

###########################*Fitting probability distribution*#########################################################

### Initial values for optimisation, based on normal approximation
 s<-qnorm(1-(0.5/2))# getting upper quartile of standard normal distribution (with mean=0 and std=1)
 v<-((u-l)/(2*s))^2 # initial with normal distribution assumption: Q3-Q1=2.s.sigma with s=0.6744898
 if (SK <= 0.5){

### Fit a normal distribution
  normalerror<-function(x,elicited,probabilities){
   a<-x[1]
   b<-exp(x[2])
   sum((pnorm(elicited,a,b)-probabilities)^2)
  }
  normalfit<-optim(c(m,0.5*log(v)),normalerror,elicited=c(Lo,l,m,u,Up),probabilities=c(0,0.25,0.5,0.75,1))
  normalparameters<-c(normalfit$par[1],exp(normalfit$par[2]))
  normallsq<-normalfit$value
  re = list (dist = "normal",pars = normalparameters, lsq = normallsq)
  return(re)
 } else {

### Fit a log normal distribution
  lnormerror<-function(x,elicited,probabilities){
   a<-x[1]
   b<-exp(x[2])
  sum((plnorm(elicited,a,b)-probabilities)^2)
  }
  std<-((log(u)-log(l))/(2*s))
  lognormalfit<-optim(c(log(m),log(std)),lnormerror,elicited=c(Lo,l,m,u,Up),probabilities=c(0,0.25,0.5,0.75,1))
  lnormparameters<-lognormalfit$par
  lnormparameters[2]<-exp(lnormparameters[2])
  lognormallsq<-lognormalfit$value
  re = list (dist = "log-normal", pars = lnormparameters, lsq = lognormallsq)
  
 }


