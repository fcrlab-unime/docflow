function Span(span)
  if span.classes:includes('personaldata') and span.classes:includes('ipaddress') then
    local content_string = pandoc.utils.stringify(span.content)
    local placeholder = "xxx.xxx"
    pointPos1 = string.find(content_string, "%.")
    pointPos2 = string.find(content_string, "%.", pointPos1+1)
    content_string = string.sub(content_string, 1, pointPos2) .. placeholder
    span.content = pandoc.Str(content_string)
    local value, position = span.classes:find('ipaddress')
    span.classes:remove(position)
  end
  return span
  end