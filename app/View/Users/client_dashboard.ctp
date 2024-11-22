<!-- Info boxes -->
<div class="container-fluid" >
    <div class="row" id="client_in_view">
        <?php if($is_plan_paid == 0 || $newsroomcount == 0){ ?>
        <!-- <div class="<?php // if($is_plan_paid == 1 &&$newsroomcount>0){ echo "col-md-2 col-sm-6"; }else{ echo "col-sm-6"; }?> col-xs-12"> -->
        <div class="col-sm-12 col-md-12 col-xs-12">
            <div class="row">
                <?php 
                    /*
                    if($is_plan_paid == 1){
                         if($newsroomcount>0){  ?>
                            <div id="Submit-pr" class="col-sm-12">    
                                <div class="info-box-content">
                                    <span class="submit-pr-btn-bx info-box-text"><a href="<?php echo SITEURL.'users/add-press-release'; ?>">Submit PR</a>
                                    </span>
                                </div>
                            </div>
                        <?php } ?>
                    <?php }else{*/
                    
                    if($is_plan_paid == 0){ ?>
                        <div id="Submit-pr" class="col-sm-6 col-md-6">    
                            <div class="info-box-content">
                                <span class="purchase-plan-btn-bx info-box-text"><a href="<?php echo SITEURL.'plans/'; ?>">Purchase PR Plan</a> </span>
                            </div>  
                        </div>
                 <?php } ?>
                <?php if($newsroomcount==0) {?>
                    <div id="Submit-pr" class="col-sm-6  col-md-6">
                        <div class="info-box-content">
                            <span class="create-newsroom-btn-bx info-box-text"><a href="<?php echo SITEURL.'users/create-newsroom'; ?>">Create newsroom</a> </span>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
        <?php }else{ ?>
            <?php  if($pressReleaseCount >= 1){ ?>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-black">
                        <a href="<?php echo SITEURL."users/press-releases/draft"; ?>">      
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512.071 512.071" style="enable-background:new 0 0 512.071 512.071;" xml:space="preserve" width="512px" height="512px"><g><g id="XMLID_1525_"> <g id="XMLID_2349_"><g id="XMLID_362_"><path id="XMLID_363_" d="M304,172.585c2.63,0,5.21-1.07,7.069-2.93c1.86-1.87,2.931-4.44,2.931-7.07s-1.07-5.21-2.931-7.07     c-1.859-1.87-4.439-2.93-7.069-2.93c-2.641,0-5.21,1.06-7.07,2.93c-1.86,1.86-2.93,4.43-2.93,7.07c0,2.63,1.069,5.21,2.93,7.07     C298.79,171.515,301.37,172.585,304,172.585z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/><path id="XMLID_364_" d="M64.01,173.255c1.85,0,3.721-0.513,5.394-1.587l29.267-18.792l0.314,2.063     c0.869,5.686,4.549,10.422,9.843,12.67c5.296,2.25,11.259,1.606,15.951-1.717l11.452-8.108c1.07,4.283,3.804,7.949,7.656,10.204     c4.275,2.502,9.391,2.95,14.039,1.229l27.76-10.289c0.489,2.83,1.719,5.512,3.613,7.771c3.137,3.739,7.734,5.884,12.615,5.884     H264c5.522,0,10-4.477,10-10s-4.478-10-10-10h-58.409c-0.346-4.364-2.426-8.492-5.866-11.361     c-4.519-3.77-10.754-4.84-16.27-2.796l-28.438,10.539c-0.929-5.611-4.604-10.272-9.852-12.483     c-5.283-2.225-11.229-1.578-15.909,1.735l-11.365,8.046l-0.272-1.785c-0.846-5.535-4.397-10.216-9.5-12.521     c-5.104-2.306-10.963-1.877-15.677,1.15l-33.846,21.732c-4.647,2.984-5.995,9.17-3.012,13.817     C57.495,171.632,60.718,173.255,64.01,173.255z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/><path id="XMLID_365_" d="M64.01,260.392c1.85,0,3.721-0.513,5.394-1.586l29.267-18.792l0.314,2.062     c0.869,5.686,4.549,10.422,9.843,12.67c5.296,2.25,11.259,1.606,15.951-1.717l11.452-8.108c1.07,4.283,3.804,7.949,7.656,10.204     c4.275,2.502,9.391,2.95,14.039,1.229l27.76-10.289c0.489,2.83,1.719,5.512,3.613,7.771c3.137,3.739,7.734,5.883,12.615,5.883     H304c5.522,0,10-4.477,10-10s-4.478-10-10-10h-98.408c-0.347-4.364-2.427-8.491-5.866-11.361     c-4.52-3.77-10.754-4.84-16.271-2.796l-28.437,10.54c-0.929-5.611-4.604-10.272-9.853-12.483     c-5.282-2.226-11.231-1.576-15.908,1.735l-11.365,8.046l-0.272-1.785c-0.846-5.536-4.397-10.216-9.501-12.521     c-5.104-2.305-10.963-1.875-15.676,1.151l-33.846,21.732c-4.647,2.984-5.995,9.17-3.012,13.817     C57.496,258.769,60.718,260.392,64.01,260.392z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/><path id="XMLID_366_" d="M64.01,347.529c1.85,0,3.721-0.513,5.394-1.587l29.267-18.792l0.315,2.062     c0.868,5.686,4.548,10.422,9.842,12.669c5.296,2.25,11.259,1.606,15.951-1.717l11.452-8.107c1.07,4.283,3.804,7.95,7.657,10.204     c4.276,2.501,9.393,2.947,14.038,1.228l27.76-10.289c0.489,2.83,1.719,5.512,3.613,7.771c3.137,3.739,7.734,5.884,12.615,5.884     H276c5.522,0,10-4.477,10-10s-4.478-10-10-10h-70.409c-0.346-4.364-2.426-8.492-5.866-11.361     c-4.519-3.77-10.754-4.841-16.27-2.796l-28.438,10.539c-0.929-5.611-4.604-10.272-9.853-12.483     c-5.282-2.226-11.231-1.577-15.908,1.735l-11.365,8.046l-0.272-1.785c-0.846-5.536-4.397-10.216-9.501-12.521     c-5.104-2.306-10.965-1.875-15.676,1.151l-33.846,21.732c-4.647,2.984-5.995,9.17-3.012,13.817     C57.495,345.906,60.718,347.529,64.01,347.529z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/><path id="XMLID_367_" d="M208.333,413.991h-2.741c-0.347-4.364-2.427-8.492-5.866-11.361c-4.521-3.77-10.755-4.841-16.271-2.796     l-28.438,10.539c-0.929-5.611-4.604-10.272-9.853-12.483c-5.282-2.225-11.231-1.577-15.908,1.735l-11.365,8.046l-0.272-1.785     c-0.846-5.535-4.397-10.216-9.5-12.521c-5.104-2.306-10.963-1.876-15.677,1.15l-33.846,21.732     c-4.647,2.984-5.995,9.17-3.012,13.817c1.911,2.976,5.133,4.599,8.425,4.599c1.85,0,3.721-0.513,5.394-1.587l29.267-18.792     l0.314,2.062c0.869,5.686,4.549,10.422,9.843,12.67c5.296,2.25,11.259,1.606,15.951-1.717l11.452-8.108     c1.07,4.283,3.804,7.95,7.656,10.204c4.275,2.502,9.393,2.95,14.038,1.229l27.761-10.289c0.489,2.83,1.719,5.512,3.613,7.771     c3.137,3.739,7.734,5.883,12.615,5.883h6.419c5.522,0,10-4.477,10-10S213.855,413.991,208.333,413.991z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                        <path id="XMLID_370_" d="M500.959,211.112c-14.816-14.815-38.924-14.815-53.74,0L364,294.331V50.035c0-16.542-13.458-30-30-30     h-20v-10c0-5.523-4.478-10-10-10s-10,4.477-10,10v10h-28v-10c0-5.523-4.478-10-10-10s-10,4.477-10,10v10h-28v-10     c0-5.523-4.478-10-10-10s-10,4.477-10,10v10h-28v-10c0-5.523-4.478-10-10-10s-10,4.477-10,10v10h-28v-10c0-5.523-4.478-10-10-10     s-10,4.477-10,10v10H74v-10c0-5.523-4.478-10-10-10s-10,4.477-10,10v10H30c-16.542,0-30,13.458-30,30v452c0,5.523,4.478,10,10,10     h344c5.522,0,10-4.477,10-10V401.812l40.933-40.933c3.905-3.905,3.905-10.237,0-14.143c-3.906-3.905-10.236-3.905-14.143,0     l-61.509,61.509l-25.455-25.456l136.852-136.852l25.456,25.455l-18.775,18.775c-3.905,3.905-3.905,10.237,0,14.142     c3.906,3.905,10.236,3.905,14.143,0l39.457-39.458C515.774,250.036,515.774,225.929,500.959,211.112z M344,492.035H20v-442     c0-5.514,4.486-10,10-10h24v24c0,5.523,4.478,10,10,10s10-4.477,10-10v-24h28v24c0,5.523,4.478,10,10,10s10-4.477,10-10v-24h28     v24c0,5.523,4.478,10,10,10s10-4.477,10-10v-24h28v24c0,5.523,4.478,10,10,10s10-4.477,10-10v-24h28v24c0,5.523,4.478,10,10,10     s10-4.477,10-10v-24h28v24c0,5.523,4.478,10,10,10s10-4.477,10-10v-24h20c5.514,0,10,4.486,10,10v264.296l-61.388,61.388     c-0.759,0.76-1.392,1.636-1.872,2.597l-39.647,79.246c-1.926,3.85-1.172,8.501,1.872,11.545c1.921,1.92,4.479,2.929,7.074,2.929     c1.519,0,3.05-0.346,4.472-1.057l79.245-39.647c0.96-0.481,1.837-1.113,2.597-1.872l7.647-7.647V492.035z M292.421,399.669     l19.981,19.982l-39.988,20.007L292.421,399.669z M486.816,250.711l-6.54,6.54l-25.456-25.455l6.541-6.541     c7.018-7.019,18.438-7.019,25.455,0C493.835,232.273,493.835,243.692,486.816,250.711z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                        <path id="XMLID_426_" d="M426.149,315.525c-2.64,0-5.21,1.07-7.08,2.93c-1.859,1.86-2.92,4.43-2.92,7.07     c0,2.63,1.061,5.21,2.92,7.07c1.87,1.86,4.44,2.93,7.08,2.93c2.631,0,5.2-1.07,7.07-2.93c1.86-1.87,2.93-4.44,2.93-7.07     s-1.069-5.21-2.93-7.07C431.35,316.595,428.78,315.525,426.149,315.525z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/></g></g>
                        </g></g> </svg>
                        </a>
                    </span>
                        <div class="info-box-content">
                        <a href="<?php echo SITEURL."users/press-releases/draft"; ?>">      
                        <span class="info-box-text">Draft PR</span>
                            <span class="info-box-number"><?php echo $draftCount; //if($draftCount>0){ echo $this->Html->link($draftCount, array('controller' => 'users', 'action' => 'press-releases','draft'), array('escape' => false)); }else{ echo $draftCount;}  ?></span>
                        </a>
                        </div>  
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow">
                        <a href="<?php echo SITEURL."users/press-releases/pending"; ?>">    
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 19.118 19.118" style="enable-background:new 0 0 19.118 19.118;" xml:space="preserve" width="512px" height="512px"><g><g>
                            <g><path d="M16.981,0H2.137C1.731,0,1.401,0.33,1.401,0.736v17.646c0,0.408,0.33,0.736,0.736,0.736h14.843    c0.406,0,0.736-0.328,0.736-0.736V0.736C17.717,0.329,17.386,0,16.981,0z M16.245,17.646H2.873V1.473h13.371V17.646z" data-original="#030104" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                                <path d="M7.64,4.668H4.946v2.693h2.693V4.668H7.64z M7.312,5.562L6.116,6.758    c-0.031,0.032-0.084,0.032-0.115,0L5.272,6.026c-0.031-0.032-0.031-0.084,0-0.117l0.175-0.172c0.031-0.033,0.083-0.033,0.116,0    L6.06,6.236l0.963-0.963c0.033-0.032,0.084-0.032,0.117,0l0.173,0.174C7.345,5.478,7.345,5.53,7.312,5.562z" data-original="#030104" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                                <rect x="8.202" y="5.274" width="6.161" height="1.481" data-original="#030104" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                                <path d="M7.64,8.18H4.946v2.692h2.693V8.18H7.64z M7.312,9.073l-1.196,1.196    c-0.031,0.032-0.084,0.032-0.115,0L5.272,9.537c-0.031-0.032-0.031-0.084,0-0.116l0.175-0.173c0.031-0.032,0.083-0.032,0.116,0    L6.06,9.747l0.963-0.963c0.033-0.032,0.084-0.032,0.117,0l0.173,0.173C7.345,8.989,7.345,9.041,7.312,9.073z" data-original="#030104" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                                <rect x="8.202" y="8.785" width="6.161" height="1.481" data-original="#030104" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                                <rect x="4.947" y="11.769" width="2.693" height="2.693" data-original="#030104" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                                <rect x="8.202" y="12.376" width="6.161" height="1.48" data-original="#030104" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                            </g></g></g> </svg></a>
                            </span>
                            <div class="info-box-content">
                            <a href="<?php echo SITEURL."users/press-releases/pending"; ?>">
                                    <span class="info-box-text">Pending PR</span>
                                    <span class="info-box-number"><?php echo $pendingCount; // if($pendingCount>0){ echo $this->Html->link($pendingCount, array('controller' => 'users', 'action' => 'press-releases','pending'), array('escape' => false)); }else{ echo $pendingCount;}  ?></span>
                            </a>
                            </div>  
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                        
                            <span class="info-box-icon bg-cyan">
                            <a href="<?php echo SITEURL."users/press-releases/approved"; ?>">    
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="512px" height="512px"><g><g><g><g><path d="M226.967,309.442l-27.384-27.384c-7.811-7.811-20.474-7.811-28.284,0c-7.811,7.81-7.811,20.473,0,28.284l41.526,41.526     c7.805,7.805,20.475,7.81,28.284,0l99.452-99.452c7.811-7.81,7.81-20.473,0-28.284c-7.81-7.811-20.473-7.811-28.284,0     L226.967,309.442z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/><path d="M426,38H313.93V20c0-11.046-8.954-20-20-20h-76c-11.046,0-20,8.954-20,20v18H86c-11.046,0-20,8.954-20,20v434     c0,11.046,8.954,20,20,20h340c11.046,0,20-8.954,20-20V58C446,46.954,437.046,38,426,38z M237.93,40h36c0,12.591,0,23.409,0,36     h-36C237.93,63.409,237.93,52.591,237.93,40z M406,472H106V78h91.93v18c0,11.046,8.954,20,20,20h76c11.046,0,20-8.954,20-20V78     H406V472z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/></g></g></g></g> </svg></a></span>
                        
                        <div class="info-box-content">
                            <a href="<?php echo SITEURL."users/press-releases/approved"; ?>">
                                <span class="info-box-text">Approved PR</span>
                                <span class="info-box-number"><?php echo $approveCount; // if($approveCount>0){ echo $this->Html->link($approveCount, array('controller' => 'users', 'action' => 'press-releases','approved'), array('escape' => false)); }else{ echo $approveCount;}  ?></span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                            <span class="info-box-icon bg-red">
                                <a href="<?php echo SITEURL."users/press-releases/embargoed"; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve" width="512px" height="512px"><g><g><path d="M30,0c-0.553,0-1,0.447-1,1v13.292c0,0.553,0.447,1,1,1s1-0.447,1-1V2.018C45.979,2.546,58,14.896,58,30   c0,15.439-12.561,28-28,28S2,45.439,2,30c0-7.46,2.9-14.479,8.166-19.764c0.391-0.392,0.389-1.024-0.002-1.414   C9.772,8.434,9.14,8.434,8.75,8.824C3.107,14.486,0,22.007,0,30c0,16.542,13.458,30,30,30s30-13.458,30-30S46.542,0,30,0z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/><path d="M28.555,33.532c0.614,0.845,1.563,1.376,2.604,1.457C31.252,34.997,31.345,35,31.437,35c0.942,0,1.848-0.372,2.519-1.044   c0.737-0.737,1.114-1.756,1.033-2.797s-0.612-1.99-1.459-2.606l-12.944-9.363c-0.396-0.286-0.945-0.242-1.293,0.104   c-0.348,0.348-0.391,0.896-0.104,1.293L28.555,33.532z M32.355,30.172c0.371,0.27,0.604,0.687,0.64,1.144   c0.036,0.456-0.13,0.903-0.453,1.227c-0.324,0.323-0.779,0.488-1.228,0.453c-0.456-0.035-0.873-0.269-1.141-0.637l-5.713-7.897   L32.355,30.172z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/></g></g> </svg>
                                </a>
                            </span>
                        
                        <div class="info-box-content">
                            <a href="<?php echo SITEURL."users/press-releases/embargoed"; ?>">
                                <span class="info-box-text">Embargoed PR</span>
                                <span class="info-box-number"><?php echo $embargoCount; // if($embargoCount>0){ echo $this->Html->link($embargoCount, array('controller' => 'users', 'action' => 'press-releases','embargoed'), array('escape' => false)); }else{ echo $embargoCount;}  ?></span>
                            </a>
                        </div> 
                    </div> 
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green">
                        <a href="<?php echo SITEURL."users/press-releases/disapproved"; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 57.149 57.15" style="enable-background:new 0 0 57.149 57.15;" xml:space="preserve"><g><g><g><g><path d="M13.109,35.265H3.558C1.596,35.265,0,33.416,0,31.144V4.966c0-2.271,1.596-4.119,3.558-4.119h9.551     c1.962,0,3.558,1.848,3.558,4.119v26.178C16.667,33.416,15.071,35.265,13.109,35.265z M3.558,3.847C3.367,3.847,3,4.283,3,4.966     v26.178c0,0.684,0.366,1.121,0.558,1.121h9.551c0.191,0,0.558-0.438,0.558-1.121V4.966c0-0.684-0.366-1.119-0.558-1.119H3.558z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/></g><g><path d="M31.73,56.302c-3.01,0-5.458-2.447-5.458-5.457c0-0.203,0.012-0.406,0.035-0.611c-0.036-0.18-0.04-0.363-0.001-0.541     c1.043-4.781-1.13-10.211-2.518-13.021c-0.12-0.242-0.237-0.478-0.349-0.691c-0.21-0.243-0.494-0.407-0.814-0.467h-0.411     c-0.059,0-0.152-0.006-0.224-0.015c-2.278-0.129-4.038-1.979-4.038-4.248V5.109c0-2.35,1.912-4.262,4.262-4.262h29.475     c0.13,0,0.255,0.016,0.373,0.047c2.761,0.291,4.881,2.615,4.881,5.412c0,1.504-0.612,2.869-1.607,3.857     c1.112,1,1.813,2.449,1.813,4.059c0,1.557-0.655,2.963-1.704,3.959c1.049,0.994,1.704,2.402,1.704,3.959     s-0.655,2.963-1.704,3.957c1.049,0.996,1.704,2.402,1.704,3.959c0,3.01-2.448,5.459-5.458,5.459l-12.607-0.002     c3.525,10.961-2.736,18.311-3.021,18.637c-0.006,0.006-0.012,0.012-0.016,0.021C35.014,55.507,33.412,56.302,31.73,56.302z      M29.333,49.857c0.026,0.16,0.026,0.322-0.007,0.482c-0.036,0.168-0.055,0.338-0.055,0.506c0,1.355,1.104,2.457,2.459,2.457     c0.789,0,1.512-0.373,1.984-1.023c0.047-0.065,0.1-0.127,0.156-0.184c0.695-0.855,5.824-7.646,1.674-17.5     c-0.195-0.463-0.146-0.992,0.133-1.41c0.277-0.42,0.746-0.672,1.25-0.672h14.762l0,0c0.656,0,1.273-0.254,1.738-0.719     c0.463-0.465,0.719-1.082,0.719-1.738c0-1.355-1.103-2.459-2.457-2.459c-0.828,0-1.5-0.672-1.5-1.5s0.672-1.5,1.5-1.5     c1.354,0,2.457-1.102,2.457-2.457s-1.103-2.459-2.457-2.459c-0.828,0-1.5-0.672-1.5-1.5s0.672-1.5,1.5-1.5     c1.354,0,2.457-1.104,2.457-2.459s-1.103-2.457-2.457-2.457c-0.828,0-1.5-0.672-1.5-1.5c0-0.799,0.625-1.477,1.422-1.52     c1.307-0.068,2.331-1.141,2.331-2.439c0-1.301-1.024-2.371-2.331-2.439c-0.061-0.004-0.118-0.01-0.176-0.02H22.214     c-0.695,0-1.262,0.566-1.262,1.262v26.143c0,0.677,0.533,1.228,1.214,1.253c0.03,0.002,0.077,0.006,0.122,0.01h0.449     c0.063,0,0.127,0.004,0.189,0.012c1.201,0.154,2.26,0.771,2.981,1.74c0.045,0.063,0.086,0.127,0.122,0.195     c0.143,0.27,0.294,0.567,0.448,0.883C28.33,39.093,30.295,44.609,29.333,49.857z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/></g></g></g></g> </a></svg>
                        </span>
                        <div class="info-box-content">
                        <a href="<?php echo SITEURL."users/press-releases/disapproved"; ?>">
                            <span class="info-box-text">Denied PR</span>
                            <span class="info-box-number"><?php echo $deninedCount; // if($deninedCount>0){ echo $this->Html->link($deninedCount, array('controller' => 'users', 'action' => 'press-releases','disapproved'), array('escape' => false)); }else{ echo $deninedCount;}  ?></span>
                        </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info">
                        <a href="<?php echo SITEURL."users/press-releases/invoices"; ?>">
                                <!-- <i class="fas fa-file-invoice-dollar"></i>
                             -->
                             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M377 105L279.1 7c-4.5-4.5-10.6-7-17-7H256v128h128v-6.1c0-6.3-2.5-12.4-7-16.9zm-153 31V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zM64 72c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8V72zm0 80v-16c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8zm144 263.88V440c0 4.42-3.58 8-8 8h-16c-4.42 0-8-3.58-8-8v-24.29c-11.29-.58-22.27-4.52-31.37-11.35-3.9-2.93-4.1-8.77-.57-12.14l11.75-11.21c2.77-2.64 6.89-2.76 10.13-.73 3.87 2.42 8.26 3.72 12.82 3.72h28.11c6.5 0 11.8-5.92 11.8-13.19 0-5.95-3.61-11.19-8.77-12.73l-45-13.5c-18.59-5.58-31.58-23.42-31.58-43.39 0-24.52 19.05-44.44 42.67-45.07V232c0-4.42 3.58-8 8-8h16c4.42 0 8 3.58 8 8v24.29c11.29.58 22.27 4.51 31.37 11.35 3.9 2.93 4.1 8.77.57 12.14l-11.75 11.21c-2.77 2.64-6.89 2.76-10.13.73-3.87-2.43-8.26-3.72-12.82-3.72h-28.11c-6.5 0-11.8 5.92-11.8 13.19 0 5.95 3.61 11.19 8.77 12.73l45 13.5c18.59 5.58 31.58 23.42 31.58 43.39 0 24.53-19.05 44.44-42.67 45.07z"/></svg>
                        </a>
                        </span>
                        <div class="info-box-content">
                        <a href="<?php echo SITEURL."users/press-releases/invoices"; ?>">
                            <span class="info-box-text">invoices</span>
                            <span class="info-box-number"><?php echo $invoiceCount; //if($invoiceCount>0){ echo $this->Html->link($invoiceCount, array('controller' => 'users', 'action' => 'invoices'), array('escape' => false)); }else{ echo $invoiceCount;}  ?></span>
                        </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?> 
    </div>
<div class="row">
    
    <?php if(!empty($transactions)){ ?>
        <div class="col-sm-6">
            <div class="card card-outline card-primary">
                <div class="card-header with-border">
                    <h3 class="card-title">Latest Invoices</h3>
                    <div class="card-tools pull-right">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa fa-minus"></i></button>                    
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table no-margin table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>S/N.</th>
                                    <th>Transaction ID</th>                             
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Amount</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php   
                                foreach ($transactions as $index => $transaction) { ?>
                                <tr>
                                    <td><?php echo $index+1;?></td>                                
                                    <td class="invoice_id"><?php echo $transaction['Invoice']['tx_id'];?></td>
                                    <td>
                                        <span class="badge bg-success"><?php echo $transaction['Invoice']['status'];?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo date("F d, Y",strtotime($transaction['Invoice']['paymant_date']));?></span>
                                    </td>
                                    <td><span class="badge bg-success"><?php echo Configure::read('PR.currency') .$transaction['Invoice']['total'];?></span></td>
                                    <td>
                                    <?php 
                                        $actions = $this->Html->link(__("View"), array(
                                                'controller' => $controller,
                                                'action' =>"Invoice_view",
                                                $transaction['Invoice']['id'],
                                        ), array('class' =>'btn-sm btn-bg-orange'));
                                
                                        if($transaction['Invoice']['txn_type']=='subscr_payment'){
                                        $actions= $this->Custom->cancelSubscriptionBtn($transaction['Invoice']['subscr_id'],$controller,'dashboard');
                                        }
                                        echo $actions;  
                                        ?>
                                    </td>   
                                    <td></td>
                                    
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.box-body -->
                <div class="card-footer clearfix">   
                    
                    <a class="btn btn-sm btn-default btn-flat float-right btn-bg-orange" href="<?php echo SITEURL.'users/invoices';?>">View All Invoices</a>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    <?php } ?>
    <?php  if(!empty($data_array)){?>
        <div class="col-sm-12 col-md-12 col-xs-12">
                <div class="card card-outline card-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title">Recently Approved Press Releases</h3>
                        <div class="card-tools pull-right">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i> </button>                     
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="card-body">
                    <div class="dataTable_wrapper table-responsive">
                  <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php
                            $tableHeading=array("Title","Read","Shared","Click Through","Release Date"); //Action
                            $tableHeaders = $this->Html->tableHeaders($tableHeading, array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                         
                                foreach ($data_array as $index => $data) {
                                    $actions=' '.$this->Html->link(__('Trash'), array('controller' => $controller,'action' => 'movetrash',$data[$model]['id']),array('class' => 'btn btn-sm btn-danger'));
                                    $title=$this->Html->link($data[$model]['title'], array('controller' => $controller, 'action' => 'view',$data[$model]['plan_id'],$data[$model]['id']));
                                     $socialShareCount=(!empty($data['0']['socialShareCount']))?$data['0']['socialShareCount']:"0";
                                    $networkFeedCount=(!empty($data['0']['networkFeedCount']))?$data['0']['networkFeedCount']:"0";
                                    $rows[] = array(
                                        $title,
                                        $data[$model]['views'],
                                        $socialShareCount,
                                        $networkFeedCount,
                                        date($dateformate, strtotime($data[$model]['release_date'])),
                                        // $actions,
                                    );
                                }
                                unset($checkcart);
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                           
                           ?>
                        </tbody>
                    </table>
                </div>
                        <?php /* ?>
                        <ul class="products-list product-list-in-box">
                            <?php
                            if(!empty($approved_pr)){
                                foreach ($approved_pr as $key => $pr) {?>
                                <li class="item">
                                    <div class="product-info">
                                        <a href="<?php echo SITEURL.'users/view/'.$pr['PressRelease']['plan_id'].'/'.$pr['PressRelease']['id']; ?>" class="product-title"><?php echo ucfirst($pr['PressRelease']['title']);?>
                                            <span class="badge bg-default pull-right">Releases ID <?php echo ucfirst($pr['PressRelease']['id']);?></span>
                                        </a>
                                        <span class="product-description">
                                            <?php echo ucfirst($pr['PressRelease']['summary']);?>
                                        </span>
                                    </div>
                                </li>
                                <?php }
                            }else{
                                echo "<li class='item'>Approved Press Releases not found.</li>";

                            } ?>
                        </ul> <?php */ ?>
                    </div>
                    <!-- /.box-body -->
                    <div class="card-footer text-center">
                        <a href="<?php echo SITEURL.'users/press-releases/approved';?>" class="uppercase">View All Releases</a>
                    </div>
                    <!-- /.box-footer -->
                </div>
        </div> 
    <?php } ?>
</div>

</div>  <!-- Close container-fluid -->