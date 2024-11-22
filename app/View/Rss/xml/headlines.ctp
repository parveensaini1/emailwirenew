<rss version="2.0">
    <channel>
        <title><?php echo strtolower($siteName); ?>.com -- Industry News Headlines</title>
            <link>https://www.emailwire.com</link>
                <description> Daily industry news and press releases published from companies around the world</description>
                <image>
                    <title><?php echo strtolower($siteName); ?></title>
                    <link><?php echo SITEURL; ?></link>
                    <url><?php echo SITEURL.'website/img/emailwire-logo.jpg'; ?></url>
                    <description>Industriy news and press releases</description>
                </image>
                <category>Industry News and Press Releases</category>
                <language>en</language>
                <?php App::uses('CakeTime', 'Utility'); ?>
                <pubDate><?php echo $this->Time->toAtom(date('Y-m-d H:i:s')); ?></pubDate>
                <lastBuildDate><?php echo $this->Time->toAtom(date('Y-m-d H:i:s')); ?></lastBuildDate>
                <managingEditor><?php echo strip_tags(Configure::read('Site.mail')); ?></managingEditor>
                <webMaster><?php echo strip_tags(Configure::read('Site.mail')); ?></webMaster>
                <copyright><?php echo $siteName;?></copyright>
                <generator><?php echo strip_tags(Configure::read('Site.headline.generator')); ?></generator>
                <docs><?php echo SITEURL; ?></docs>
                <?php
                if(!empty($data_arr)){

                foreach ($data_arr as $key => $data) { 
                    
                    $siteurl=SITEURL.'release/'.$data['PressRelease']['slug'];
                    $wordLimit="500";
                    $bodyText =(!empty($data['PressRelease']['summary']))?h(strip_tags($data['PressRelease']['summary'])):h(strip_tags($data['PressRelease']['body']));
                    $bodyText = $this->Text->truncate($bodyText, $wordLimit, array(
                        // 'ending' => '...',
                        'exact'  => true,
                        'html'   => true,
                    ));
                ?>
                <item>
                    <title><?php echo $data['PressRelease']['title']; ?></title>
                    <link><?php echo $siteurl; ?></link>
                    <description><?php echo $bodyText;?><img  border="0" src="<?php echo SITEURL."rss/gif?v=".$data['PressRelease']['id']; ?>" />
                    </description>
                    <pubDate><?php echo date('Y-m-d', strtotime($data['PressRelease']['release_date'])); ?></pubDate>
                    </item>
                 <?php 
                } 
            }
            ?>   
    </channel>
</rss>