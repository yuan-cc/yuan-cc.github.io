# AM3
Astrophysical Multiwavelength and MultiMessenger computation software

## Prerequisites:
1. Python3.7 for using the Python interface
    - numpy
    - pybind11

2. C++
    - c++11 or above
    - gsl library for mathematical special functions in C++: `sudo apt install libgsl-dev`
    - eigen library for matrix operations (currently included in the repo)


## Making AM3:
Simply use `make` in the directory of the make file to compile and link AM3 for the first time. For remaking remember to `make clean` first. 

## Importing `pybind_core` to python
It is important to add the path of `pybind_core` to your `PYTHONPATH` or
append it at the beginning of your program: `../AM3/libpython`

## Changes made to AM3 for expanding blobs (CY, December 06, 2022)

### Summary
1. [***AM3 modules***] Added a new command `process_expandingblob` to `class SimulationManager` in `core.h, core.cc` to make the particles densities consistent with an expanding shell. If `Rbolb` depends on time, `sim.process_expandingblob = 1` should be implemented (default value is `0`).
2. [***AM3 modules***] Added a new function `int Binary_Search(std::vector <double> input_list, double x, int start,int end);` to `math.h, math.cc` for locating the position `int index` of a value `double x` in a 1D vector `vector <double> input_list`, given the start `int start = 0` and end `int end = input_list.size() - 1` indices.
3. [***AM3 modules***] Added a new header file `AM3modules.h` to `/include/AM3`. All AM3 related header files are included in `AM3modules.h`. In `main.cc`, one only need to include `AM3/AM3modules.h`.
4. [***Astro Source modules***] The time evolution of source parameters, e.g., comoving blob radius, bulk Lorentz factor, magnetic field strength, etc, and proton/electron injection spectra, can be simulated in `class SourceParams` in `/AstroSources/include/sourceparams.h`. (A detailed description can be found below)
5. [***Astro Source modules***] Added a format output class `class OutPut` to write the photon/neutrino fluxes, fluences, and light curves to a specified directory, e.g., `./results/data/`.
6. [***Astro Source modules***] Cosmic gamma-gamma attentuation optical depth with EBL can be obtained via the function `double OutPut::get_EBL_opticaldepth(double redshift, double E_obs)`.

### Data flow between AM3 modules and AstroSource modules

### SourceParams.h

***Parameters***
```
        double t_start, //start time, in the observer frame [s]
               t_stop,  //stop time, in the observer frame [s]
               R0eng,   //initial blob radius in engine frame [cm]
               Lorentz0 = 1,//initial blob Lorentz factor
               theta_Jet,  //opening angle, for jets
               Ek, //blast wave isotropic kinetic energy [ergs]
               LuminoDistance, // liminosity distance [Mpc]
               RedShift, // source redshift
               theta_view = 0.0, // view angle, on-axis = 0.0
               epsilonB = 0.01, //fraction of ISM energy converted to magnetic field 
               epsilonE = 0.1, // fraction of ISM energy converted to accelerated electrons
               epsilonPro = 0.1, // fraction of ISM energy converted to accelerated protons
               E_spec_index = 2.2, //electron spectral index, dN/dE ~ E^(-E_spec_index) 
               Pro_spec_index = 2.2, //proton spectral index
               fractionPro_acc = 0.1, // number fraction of accelerated protons
               fractionE_acc = 0.1; // number fraction of accelerated electrons: (# of accelerated)/(total electron number)

        double dt_obs, t_obs; // delta t in observer's frame, current time in the loop
        double ExternalDensity(double); // User should define the external density [g/cm^3] in sourceparams.cc as a function of radius [cm]
        double ISMmass(double); // User should define external medium mass [g] in sourceparams.cc as a function of radius [cm]
                                // by integrating ExternalDensity(double) over volume
```
***Functions***
```
SourceParams SP;
SP.xxx = xxx; //input initial/physical parameters

SP.initialize_dynamic(); //prepare tables for physical quantities
SP.PrepareDynamicTables(); // get tables for Lorentz factor, Rblob_obs, magnetic field strength for later output/interpolation
SP.output_dynamic("./results/data/");
```
The following functions can be called in a loop:
```
double get_CoBlobRadius(double t_obs); // t_start < t_obs< t_stop, in observer's frame
double get_ObsBlobRadius(double t_obs);
double get_LorentzFactor(double t_obs);
double get_NumberDensity(double t_obs);
double get_MagneticField(double t_obs);

double* get_externalE(double t_obs,int e_inj_max); 
double* get_externalPro(double t_obs, int pro_inj_max);

```

For example
```
do{
        rp.Rblob = SP.get_CoBlobRadius(SP.t_obs);  // set comoving blob radius
        sim.Update_B(SP.get_MagneticField(SP.t_obs)); // set magnetic field
        
        sim.Estimate_MaximumLeptonEnergy();
        sim.Estimate_MaximumProtonEnergy();

        sim.External_Electrons(SP.get_externalE(SP.t_obs, rp.e_inj_max));  // inject electrions from the function get_externalE
        sim.External_Protons(SP.get_externalPro(SP.t_obs, rp.PRO_inj_max));  // inject protons from function get_externalPro

        sim.EVO();
        
        SP.dt_obs = rp.FRACt * rp.Rblob / c0 * (1+SP.RedShift) / SP.get_LorentzFactor(SP.t_obs); // from comoving frame dt to obs frame
        SP.t_obs += SP.dt_obs;  //record current time in observer's frame
        
        if(SP.t_obs > SP.t_stop) SP.t_obs = SP.t_stop; // to prevent time overflow      

    }while(SP.t_obs < SP.t_stop);
```

## Events

<hr style="height:2px;border-width:0;color:gray;background-color:#B3A1BF">
<details><summary> Conference Talks & Posters</summary>
  <ul>
 <li> 07/2021 - contributed talk, European Physical Society Conference on High Energy Physics (EPS-HEP)</li>
 <li> 04/2021 - contributed talk, APS April Meeting (virtual)</li>
  <li>08/2020 - contributed taik, Time-Domain High-Energy Messenger Astrophysics Workshop, University of Kyoto, Japan</li> 
   <li>07/2019 - (poster)36th International Cosmic Ray Conference (ICRC), Madison, WI</li>
  <li>06/2019 - contributed talk, IGC@25: Multimessenger Universe Workshop, State College, PA</li>
   <li>01/2018 - contributed talk, APS April meeting, Columbus, OH</li>
   </ul>
</details>

 <details><summary> Seminars & Colloquia</summary>
  <ul> 
   <li> 03/2021 - CTC talk series, University of Maryland </li>
   <li> 12/2021 - HEP seminar, Columbia University [<a href="https://yuan-cc.github.io/files/columbia_slides.pdf">Slides</a>]</li>
   <li> 11/2021 - talk, THAT seminar, DESY (virtual)</li>
   <li> 10/2021 - talk, astronomy colloquium, UNLV (virtual)</li>
   <li>10/2020 - CCAPP AstroParticle Lunch, OSU (virtual) </li>
   <li>10/2020 - astronomical seminar, Tohoku University, Japan (virtual)</li> 
   <li>09/2020 - lunch talk, Dept. of Astronomy & Astrophysics, Penn State </li>
   <li>08/2015 - lunch talk, Dept. of Astronomy & Astrophysics, Penn State</li>
  </ul>
</details>  
   
<hr style="height:2px;border-width:0;color:gray;background-color:#B3A1BF">


### 2022
**October 01**<br />
Move to Zeuthen, Germany and start my postdoctoral position at DESY.

**August 13**<br />
Penn State Summer 2022 Commencement ceremonies. 

**June 28**<br />
My PhD dissertation is approved by the Committee and the Graduate School.

**June 17**
* The type 003 Aircraft Carrier 'Fujian' (福建舰) featuring an electromagnetic catapult is now finally announced and launched. Congratulations to Chinese Navy! 
* Our SGRB paper with Kohta, Peter, Imre, Dafne, and Asaf has now been published in ApJ, and is available at https://doi.org/10.3847/1538-4357/ac6ddf.

**April 19, 10 am (EDT) <br />
PhD defense (hybrid: Davey Lab 339 and zoom link TBD)**

~~April 10 - APS April meeting, New York City (cancelled)~~

**March 16** <br />
CTC talk series, University of Maryland

<details><summary>2021</summary>
 <ul>
<li> 12/2021 - HEP seminar, Columbia University [<a href="https://yuan-cc.github.io/files/columbia_slides.pdf">Slides</a>]</li>
<li> 11/2021 - talk, THAT seminar, DESY (virtual)</li>
<li> 10/2021 - talk, astronomy colloquium, UNLV (virtual)</li>
<li> 07/2021 - contributed talk, European Physical Society Conference on High Energy Physics (EPS-HEP)</li>
<li> 04/2021 - contributed talk, APS April Meeting (virtual)</li>
   </ul>
</details>

<details><summary>2020</summary>
  <ul>
   <li>10/2020 - CCAPP AstroParticle Lunch, OSU (virtual) </li>
   <li>10/2020 - astronomical seminar, Tohoku University, Japan (virtual)</li> 
   <li>09/2020 - lunch talk, Dept. of Astronomy & Astrophysics, Penn State </li>
   <li>08/2020 - contributed taik, Time-Domain High-Energy Messenger Astrophysics Workshop, University of Kyoto, Japan</li>
   </ul>
</details>

<details><summary>2019 - </summary>
 <ul>
<li>07/2019 - (poster)36th International Cosmic Ray Conference (ICRC), Madison, WI</li>
<li>06/2019 - contributed talk, IGC@25: Multimessenger Universe Workshop, State College, PA</li>
<li>04/2018 - passed the Doctoral Comprehensive Exam </li>
<li>01/2018 - contributed talk, APS April meeting, Columbus, OH</li>
<li>10/2016 - passed the candidancy exam </li>
<li>06/2016 - obtained B.Sc. of Astronomy from Nanjing University</li>
<li>08/2015 - lunch talk, Dept. of Astronomy & Astrophysics, Penn State</li>
<li>07/2015 - REU internship, host institute: Dept. of Astronomy & Astrophysics, Penn State</li>

  </ul>
</details>
