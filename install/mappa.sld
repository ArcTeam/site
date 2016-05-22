<?xml version="1.0" encoding="UTF-8"?>
<StyledLayerDescriptor version="1.0.0" xmlns="http://www.opengis.net/sld" xmlns:ogc="http://www.opengis.net/ogc"
  xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://www.opengis.net/sld http://schemas.opengis.net/sld/1.0.0/StyledLayerDescriptor.xsd">
  <NamedLayer>
    <Name>arcteam</Name>
    <UserStyle>
      <Name>arcteam</Name>
      <Title>Categorie lavori</Title>
      <Abstract>Lavori svolti dalla ditta suddivisi per categorie</Abstract>
      <FeatureTypeStyle>
      	<Rule>
       		<Name>Archeologia</Name>
       		<Title>Archeologia</Title>
       		<ogc:Filter>
         		<ogc:PropertyIsEqualTo>
           			<ogc:PropertyName>cat</ogc:PropertyName>
           			<ogc:Literal>1</ogc:Literal>
         		</ogc:PropertyIsEqualTo>
       		</ogc:Filter>
       		<PointSymbolizer>
         		<Graphic>
           			<Mark>
             			<WellKnownName>ttf://FontAwesome Regular#0xf19c</WellKnownName>
             			<Fill>
               				<CssParameter name="fill">#636363</CssParameter>
             			</Fill>
                  	</Mark>
           			<Size>24</Size>
         		</Graphic>
       		</PointSymbolizer>
     	</Rule>
        <Rule>
       		<Name>Documentazione</Name>
       		<Title>Documentazione</Title>
       		<ogc:Filter>
         		<ogc:PropertyIsEqualTo>
           			<ogc:PropertyName>cat</ogc:PropertyName>
           			<ogc:Literal>2</ogc:Literal>
         		</ogc:PropertyIsEqualTo>
       		</ogc:Filter>
       		<PointSymbolizer>
         		<Graphic>
           			<Mark>
             			<WellKnownName>ttf://FontAwesome Regular#0xf03d</WellKnownName>
             			<Fill>
               				<CssParameter name="fill">#636363</CssParameter>
             			</Fill>
           			</Mark>
           			<Size>24</Size>
         		</Graphic>
       		</PointSymbolizer>
     	</Rule>
        <Rule>
       		<Name>Informatica</Name>
       		<Title>Informatica</Title>
       		<ogc:Filter>
         		<ogc:PropertyIsEqualTo>
           			<ogc:PropertyName>cat</ogc:PropertyName>
           			<ogc:Literal>3</ogc:Literal>
         		</ogc:PropertyIsEqualTo>
       		</ogc:Filter>
       		<PointSymbolizer>
         		<Graphic>
           			<Mark>
             			<WellKnownName>ttf://FontAwesome Regular#0xf108</WellKnownName>
             			<Fill>
               				<CssParameter name="fill">#636363</CssParameter>
             			</Fill>
           			</Mark>
           			<Size>24</Size>
         		</Graphic>
       		</PointSymbolizer>
     	</Rule>
        <Rule>
       		<Name>Didattica</Name>
       		<Title>Didattica</Title>
       		<ogc:Filter>
         		<ogc:PropertyIsEqualTo>
           			<ogc:PropertyName>cat</ogc:PropertyName>
           			<ogc:Literal>4</ogc:Literal>
         		</ogc:PropertyIsEqualTo>
       		</ogc:Filter>
       		<PointSymbolizer>
         		<Graphic>
           			<Mark>
             			<WellKnownName>ttf://FontAwesome Regular#0xf19d</WellKnownName>
             			<Fill>
               				<CssParameter name="fill">#636363</CssParameter>
             			</Fill>
           			</Mark>
           			<Size>24</Size>
         		</Graphic>
       		</PointSymbolizer>
     	</Rule>
        <Rule>
       		<Name>Laboratorio</Name>
       		<Title>Laboratorio</Title>
       		<ogc:Filter>
         		<ogc:PropertyIsEqualTo>
           			<ogc:PropertyName>cat</ogc:PropertyName>
           			<ogc:Literal>5</ogc:Literal>
         		</ogc:PropertyIsEqualTo>
       		</ogc:Filter>
       		<PointSymbolizer>
         		<Graphic>
           			<Mark>
             			<WellKnownName>ttf://FontAwesome Regular#0xf0c3</WellKnownName>
             			<Fill>
               				<CssParameter name="fill">#636363</CssParameter>
             			</Fill>
           			</Mark>
           			<Size>24</Size>
         		</Graphic>
       		</PointSymbolizer>
     	</Rule>
        <Rule>
       		<Name>Convegni</Name>
       		<Title>Convegni</Title>
       		<ogc:Filter>
         		<ogc:PropertyIsEqualTo>
           			<ogc:PropertyName>cat</ogc:PropertyName>
           			<ogc:Literal>6</ogc:Literal>
         		</ogc:PropertyIsEqualTo>
       		</ogc:Filter>
       		<PointSymbolizer>
         		<Graphic>
           			<Mark>
             			<WellKnownName>ttf://FontAwesome Regular#0xf0e6</WellKnownName>
             			<Fill>
               				<CssParameter name="fill">#636363</CssParameter>
             			</Fill>
           			</Mark>
           			<Size>24</Size>
         		</Graphic>
       		</PointSymbolizer>
     	</Rule>
      </FeatureTypeStyle>
    </UserStyle>
  </NamedLayer>
</StyledLayerDescriptor>
