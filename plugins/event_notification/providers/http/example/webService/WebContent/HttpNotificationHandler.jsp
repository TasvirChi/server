<%@page import="java.io.BufferedReader"%>
<%@page import="org.w3c.dom.Element"%>
<%@page import="com.borhan.client.utils.XmlUtils"%>
<%@page import="lib.Borhan.HttpNotificationHandler"%>
<%@page import="com.borhan.client.types.BorhanHttpNotification"%>
<%@page import="com.borhan.client.utils.ParseUtils"%>
<%@page import="lib.Borhan.RequestHandler"%>
<%

BufferedReader reader = request.getReader();
StringBuffer sb = new StringBuffer("");
String line;
while ((line = reader.readLine()) != null){
	sb.append(new String(line.getBytes("ISO-8859-1"), "UTF-8"));
}
reader.reset();

String xml = sb.toString();
String signature = request.getHeader("x-borhan-signature");
RequestHandler.validateSignature(xml, SessionConfig.BORHAN_ADMIN_SECRET, signature);

int dataOffset = xml.indexOf("data=");
if(dataOffset < 0) {
	System.out.println("Couldn't find data");
}

String xmlData = xml.substring(5);
Element xmlElement = XmlUtils.parseXml(xmlData);
BorhanHttpNotification httpNotification = ParseUtils.parseObject(BorhanHttpNotification.class, xmlElement);

HttpNotificationHandler handler = new HttpNotificationHandler();
handler.handle(httpNotification);
handler.finalize();

%>